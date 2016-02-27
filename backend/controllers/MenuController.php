<?php

namespace worstinme\zoo\backend\controllers;

use Yii;

use worstinme\zoo\backend\models\Applications;
use worstinme\zoo\backend\models\Menu;
use worstinme\zoo\backend\models\MenuSearch;

use yii\web\NotFoundHttpException;

class MenuController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($menu = null)
    {

        if ($menu === null) {
            $model = new Menu;
        }
        else {
            $model = Menu::findOne($menu);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Настройки сохранены'));
        }

        return $this->render('update',[
            'model'=>$model
        ]);
    }

}