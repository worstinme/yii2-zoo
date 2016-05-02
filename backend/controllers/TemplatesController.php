<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use Yii;
use worstinme\zoo\helpers\AppHelper;
use yii\web\NotFoundHttpException;

class TemplatesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'template','template-save'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'template','template-save'],
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

    public function actionIndex() {
        
        $app = $this->getApp();

        $templates = array_keys($app->templatesConfig);
        
        return $this->render('index', [
            'templates'=>$templates,
        ]);

    }

    public function actionTemplate() {

        $app = $this->getApp();

        $name = Yii::$app->request->get('template');

        $template = $app->getTemplate($name);

        return $this->render('template', [ 
            'name'=>$name,
            'template'=>$template,
        ]);

    }

    public function actionTemplateSave($renderer = null) {

        $request = Yii::$app->request;

        $app = $this->getApp();

        if($request->isPost) {  

            $rows = $request->post('rows');
            $name = $request->post('name');

            if (is_array($rows) && count($rows)) {
                
                foreach ($rows as $key=>$row) {
                    if (empty($row['items']) || !count($row['items'])) {
                        unset($rows[$key]);
                    }
                }

                $app->setTemplate($name,['rows'=>$rows]);
                $app->save();     

                echo 'шаблон сохранен';

            }
            else {
                print_R($_POST);
            }
           

            
        }
        
    }

}