<?php
/**
 * Created by PhpStorm.
 * User: spendlively
 * Date: 11.08.18
 * Time: 9:58
 */

namespace app\controllers;

use app\models\Category;
use app\models\Comment;
use app\models\News;
use Yii;
use yii\base\Controller;

/**
 * Controller for creating testing data
 *
 * Class CreateController
 * @package app\controllers
 */
class CreateController extends Controller
{
    /**
     * Data for the category table
     *
     * @var array
     */
    public $categories = [
        [1, null, 'Category 1'],
        [2, null, 'Category 2'],
        [3, null, 'Category 3'],

        [4, 1, 'Category 11'],
        [5, 2, 'Category 22'],
        [6, 3, 'Category 33'],

        [7, 4, 'Category 111'],
        [8, 5, 'Category 222'],
        [9, 6, 'Category 333'],
    ];

    /**
     * Data for the news table
     *
     * @var array
     */
    public $news = [
        [null, 1],
        [null, 1],
        [null, 1],
        [null, 1],

        [null, 2],
        [null, 2],
        [null, 2],
        [null, 2],

        [null, 3],
        [null, 3],
        [null, 3],
        [null, 3],

        [null, 4],
        [null, 4],
        [null, 4],
        [null, 4],

        [null, 5],
        [null, 5],
        [null, 5],
        [null, 5],

        [null, 6],
        [null, 6],
        [null, 6],
        [null, 6],

        [null, 7],
        [null, 7],
        [null, 7],
        [null, 7],

        [null, 8],
        [null, 8],
        [null, 8],
        [null, 8],

        [null, 9],
        [null, 9],
        [null, 9],
        [null, 9],
    ];

    /**
     * News title template string
     *
     * @var string
     */
    public $newsTitleTemplate = 'Header';

    /**
     * News short text template string
     *
     * @var string
     */
    public $newsShortTextTemplate = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

    /**
     * News text template string
     *
     * @var string
     */
    public $newsTextTemplate = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';

    /**
     * News alias template string
     *
     * @var string
     */
    public $newsAliasTemplate = 'alias';

    /**
     * Comments author template string
     *
     * @var string
     */
    public $commentNameTemplate = 'Guest';

    /**
     * Comments text template string
     *
     * @var string
     */
    public $commentTextTemplate = 'Comment\'s text';

    /**
     * Trancates selected table
     *
     * @param $tableName
     *
     * @return \yii\db\DataReader
     *
     * @throws \yii\db\Exception
     */
    public function trancateTable($tableName)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("DELETE FROM {$tableName};");
        return $command->query();
    }

    /**
     * Fills category table
     *
     * @return string
     *
     * @throws \yii\db\Exception
     */
    public function actionCategories()
    {

        $this->trancateTable(Category::tableName());

        foreach ($this->categories as $category) {

            $model = new Category();

            $model->id = $category[0];
            $model->parent_id = $category[1];
            $model->name = $category[2];

            $model->save();
        }

        return sprintf("%s categories created", count($this->categories));
    }

    /**
     * Fills news table
     *
     * @return string
     *
     * @throws \yii\db\Exception
     */
    public function actionNews()
    {

        $this->trancateTable(News::tableName());

        $increment = 1;

        $date = new \DateTime('-1 year');
        $dateInterval = \DateInterval::createFromDateString('1 day');

        foreach ($this->news as $news) {

            $model = new News();

            $model->id = $news[0];
            $model->category_id = $news[1];
            $model->title = sprintf("%s %s", $this->newsTitleTemplate, $increment);
            $model->short_text = sprintf("%s", $this->newsShortTextTemplate);;
            $model->text = sprintf("%s", $this->newsTextTemplate);
            $model->is_active = true;
            $model->alias = sprintf("%s_%s", $this->newsAliasTemplate, $increment);

            $model->date = $date->format('Y-m-d H:i:s');
            $date->add($dateInterval);

            $model->save();

            $increment++;
        }

        return sprintf("%s news created", count($this->news));
    }

    /**
     * Fills comment table
     *
     * @return string
     *
     * @throws \yii\db\Exception
     */
    public function actionComments()
    {
        $this->trancateTable(Comment::tableName());

        $firstNew = News::find()->orderBy(['id' => SORT_ASC])->one();
        $firstNewId = (int)$firstNew->id;
        $count = count($this->news);

        for ($i = 0; $i < $count; $i++) {

            $model = new Comment();

            $model->id = null;
            $model->news_id = $firstNewId;
            $model->name = $this->commentNameTemplate;
            $model->text = sprintf("%s №%s", $this->commentTextTemplate, $firstNewId);

            $model->save();
            $firstNewId++;
        }

        return sprintf("%s comments created", $count);
    }

    /**
     * Aggregating method, fills all tables
     *
     * @return string
     *
     * @throws \yii\db\Exception
     */
    public function actionAll()
    {

        $result = '';

        $result .= $this->actionCategories() . "<br />";
        $result .= $this->actionNews() . "<br />";
        $result .= $this->actionComments() . "<br />";

        return $this->render('all', [
            'result' => $result,
        ]);
    }
}
