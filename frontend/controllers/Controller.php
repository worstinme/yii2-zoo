<?php

namespace worstinme\zoo\frontend\controllers;

use Yii;

use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\S as s;

use yii\web\NotFoundHttpException;

class Controller extends \yii\web\Controller
{
    public function getApp()
    {
        return Yii::$app->zoo->getApplication($this->id);
    }

    public function init()
    {
        $this->app->lang = substr(Yii::$app->request->get('lang', Yii::$app->language), 0, 2);
        Yii::$app->language = $this->app->lang;
        Yii::trace('Language: ' . $this->app->lang);
        parent::init();
    }

    public function actionSearch()
    {

        $searchModel = new ItemsSearch();
        $searchModel->app_id = $this->app->id;
        $searchModel->regBehaviors();

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('search', [
            'app' => $app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionIndex($app = null)
    {

        $app = $app === null ? $this->app : $app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('application', [
            'app' => $app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionItem($id)
    {
        $model = $this->getItem($id);

        return $this->render('item', [
            'model' => $model,
        ]);
    }

    public function actionCategory($id)
    {

        $app = $category->app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();
        $searchModel->category = [$category->id];

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('category', [
            'category' => $category,
            'app' => $app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function getAlternateUrl($lang)
    {
        if ($this->model !== null) {
            if ($this->model instanceof Categories || $this->model instanceof Items) {
                foreach ($this->model->alternates as $alternate) {
                    if ($alternate->lang == $lang) {
                        return $alternate->url;
                    }
                }
            }
        }
        return [$this->id . '/index', 'lang' => $lang];
    }

    protected function getItem($id)
    {
        if (($item = \worstinme\zoo\frontend\models\Items::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $item;
    }

    protected function getCategory($id)
    {
        if (($category = \worstinme\zoo\frontend\models\Categories::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $category;
    }
}
