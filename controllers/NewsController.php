<?php
/**
 * Created by PhpStorm.
 * User: spendlively
 * Date: 11.08.18
 * Time: 12:54
 */

namespace app\controllers;

use app\models\Comment;
use app\models\News;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Controller displaing news and comments
 *
 * Class NewsController
 * @package app\controllers
 */
class NewsController extends Controller
{
    /**
     * News page action
     *
     * @return string|\yii\web\Response
     *
     * @throws HttpException
     */
    public function actionNews()
    {
        $commentModel = new Comment();
        if ($commentModel->load(Yii::$app->request->post())) {
            if ($commentModel->save()) {
                Yii::$app->session->setFlash('success', 'Your comment is saved');
                return $this->refresh();

            } else {
                Yii::$app->session->setFlash('error', 'Validation error');
            }
        }

        $alias = Yii::$app->getRequest()->getQueryParam('alias');

        $tableName = News::tableName();
        $query = "SELECT * FROM {$tableName} WHERE alias = :alias";
        $news = News::findBySql($query, [':alias' => strtolower($alias)])->asArray()->one();

        if (!$news) {
            throw new HttpException(404, 'Page not found');
        }

        $comments = Comment::find()->asArray()->where(['news_id' => $news['id']])->all();

        return $this->render('news', [
            'news' => $news,
            'comments' => $comments,
            'commentModel' => $commentModel,
        ]);
    }
}
