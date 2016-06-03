<?php

namespace worstinme\zoo\backend\controllers;

use Yii;
use worstinme\zoo\backend\models\Widgets;
use worstinme\zoo\backend\models\WidgetsSearch;
use yii\web\NotFoundHttpException;

class WidgetsController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'update', 'delete','create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'delete','create'],
                        'roles' => $this->module->accessRoles !== null ? $this->module->accessRoles : ['admin','moder'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','delete'],
                    //'sort' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new WidgetsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        return $this->render('create', [
            'widgets'=>$this->findWidgets(),
        ]);
        
    }

    public function actionUpdate($id = null)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->widgetModel->validate() && $model->save()) {

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'model' => $model->getAttributes(),
                ];
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSort() {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sort = Yii::$app->request->post('sort');

        $result = [];

        if ($sort !== null && is_array($sort)) {
            
            foreach ($sort as $key => $value) {
                
                $widget = $this->findModel($key);
                $widget->sort = (int)$value;
                $widget->save();

                $result[] = $widget->errors;

            }

            return [
                'success' => true,
                'result'=>$result,
            ];

        }

        return [
            'success' => false,
        ];
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if ($id === null && ($widget = $this->checkWidget(Yii::$app->request->get('widget'))) !== null) {
            $model = new Widgets;
            $model->widgetModel = new $widget;
            $model->type = Yii::$app->request->get('widget');
            return $model;
        }
        elseif (($model = Widgets::findOne($id)) !== null && ($widget = $this->checkWidget($model->type)) !== null) {
            $model->widgetModel = new $widget;
            $model->widgetModel->setAttributes(\yii\helpers\Json::decode($model->params));
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function checkWidget($widget) {

        if (!empty($widget) && file_exists(Yii::getAlias('@worstinme/zoo/widgets/models/'.$widget.'.php'))) {
            return 'worstinme\zoo\widgets\models\\'.$widget;
        } else {
            return null;
        }
    }

    protected function findWidgets() {

        $widgets = [];

        $path = Yii::getAlias('@worstinme/zoo/widgets/models/');

        $widgetModels = \yii\helpers\FileHelper::findFiles($path);

        foreach ($widgetModels as $key => $model) {
            $model = str_replace($path, '', rtrim($model,".php"));
            $widgets[$model] = "worstinme\zoo\widgets\models\\".$model;
        }

        return $widgets;
    }
}
