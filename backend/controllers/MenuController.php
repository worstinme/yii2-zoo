<?php

namespace worstinme\zoo\backend\controllers;

use Yii;

use worstinme\zoo\backend\models\Applications;
use worstinme\zoo\backend\models\Menu;
use worstinme\zoo\backend\models\MenuSearch;

use yii\web\NotFoundHttpException;

class MenuController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update','delete'],
                        'roles' => $this->module->accessRoles !== null ? $this->module->accessRoles : ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','delete'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $groups = Menu::find()->select(['menu'])->distinct()->indexBy('menu')->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groups'=>$groups,
        ]);
    }

    public function actionUpdate($menu = null)
    {

        if ($menu === null) {
            $model = new Menu;
        }
        else {
            $model = $this->findModel($menu);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update',[
            'model'=>$model
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}