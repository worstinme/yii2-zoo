<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use Yii;
use worstinme\zoo\backend\models\Applications;
use worstinme\zoo\backend\models\Items;

use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'create'],
                        'roles' => $this->module->accessRoles !== null ? $this->module->accessRoles : ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => $this->module->accessRoles !== null ? $this->module->accessRoles : ['admin','moder'],
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
    	$applications = Applications::find()->All();

        $model = new Applications;

        return $this->render('index',[
            'applications'=>$applications,
            'model'=>$model,
        ]); 
    }

    // создание приложения

    public function actionCreate()
    {
        
        $model = new Applications;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $controller = strtolower($model->name);

            $urlRules = "<code>".\yii\helpers\Html::encode("'$controller/search'=>'$controller/default/search',\n
                '$controller/<b:[\w\-]+>/<c:[\w\-]+>/<d:[\w\-]+>/<e:[\w\-]+>'=>'$controller/default/abcde',\n
                '$controller/<b:[\w\-]+>/<c:[\w\-]+>/<d:[\w\-]+>'=>'$controller/default/abcd',\n
                '$controller/<b:[\w\-]+>/<c:[\w\-]+>'=>'$controller/default/abc',\n
                '$controller/<b:[\w\-]+>'=>'$controller/default/ab',\n
                '$controller'=>'$controller/default/index',")."</code>";
            
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Приложение добавлено. Добавьте в web.php правила для обработки ссылок'.$urlRules)); 

            return $this->redirect(['index']);
            
        } 

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    // настройки приложения

    public function actionUpdate()
    {

        $model = $this->getApp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Настройки сохранены'));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

}