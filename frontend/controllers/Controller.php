<?php

namespace worstinme\zoo\frontend\controllers;

use worstinme\zoo\models\ApplicationsContent;
use Yii;

use worstinme\zoo\models\Items;
use worstinme\zoo\models\Categories;
use worstinme\zoo\frontend\models\ItemsSearch;

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
        parent::init();
    }

    public function actionSearch()
    {
        $searchModel = new ItemsSearch([
            'app_id'=>$this->app->id,
        ]);
        $searchModel->regBehaviors();
        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('search', [
            'app' => $this->app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex()
    {
        if (($model = ApplicationsContent::findOne(['app_id'=>$this->app->id,'lang'=>$this->app->lang])) === null) {
            throw new NotFoundHttpException();
        }

        $searchModel = new ItemsSearch([
            'app_id'=>$this->app->id,
        ]);

        $searchModel->regBehaviors();
        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('application', [
            'app' => $this->app,
            'model'=>$model,
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
        $category = $this->getCategory($id);
        $searchModel = new ItemsSearch([
            'app_id'=>$this->app->id,
            'category'=>[$category->id]
        ]);
        $searchModel->regBehaviors();
        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('category', [
            'app' => $this->app,
            'category' => $category,
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
