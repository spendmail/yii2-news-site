<?php

namespace app\controllers;

use app\models\Category;
use app\models\News;
use Yii;
use yii\data\Pagination;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $categoryId = (int)Yii::$app->getRequest()->getQueryParam('category');

        $sort = new Sort([
            'attributes' => [
                'date' => [
                    'asc' => ['date' => SORT_ASC],
                    'desc' => ['date' => SORT_DESC],
                    'label' => 'Sort by Date',
                ],
            ],
        ]);

        $orderBy = empty($sort->orders) ? ['date' => SORT_DESC] : $sort->orders;

        $news = News::find()->asArray()->orderBy($orderBy);
        if ($categoryId) {
            $news = $news->where(['category_id' => $categoryId]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 3,
            'totalCount' => $news->count(),
        ]);

        $news = $news
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $parentCategory = $categoryId ? Category::find()->asArray()->where(['id' => $categoryId])->one() : null;

        return $this->render('index', [
            'parentCategory' => $parentCategory,
            'categories' => $this->getCategories($categoryId),
            'news' => $news,
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
    }

    /**
     * Returns categories array by category id
     *
     * @param $categoryId
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCategories($categoryId)
    {
        $whereCondition = $categoryId ? "c.parent_id = {$categoryId}" : "c.parent_id is NULL";
        $query = "
          SELECT 
            c.id id, 
            c.parent_id parent_id, 
            c.name name,
            COUNT(n.id) count
          FROM category c
          INNER JOIN news n ON n.category_id = c.id
          WHERE {$whereCondition}
          GROUP BY c.id, c.parent_id, c.name
          HAVING count > 0  
        ";

        $categories = Category::findBySql($query)->asArray()->all();

        return $categories;
    }

    /**
     * Login action
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
