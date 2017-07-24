<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\helpers\Inflector;
use Yii;
use worstinme\zoo\backend\models\Categories;
use yii\web\NotFoundHttpException;

class CategoriesController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'update', 'delete','sort'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'delete','sort'],
                        'roles' => $this->module->accessRoles !== null ? $this->module->accessRoles : ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','delete'],
                    'sort' => ['post'],
                ],
            ],
        ];
    }

    public function actionAliasCreate()
    {
        $alias = Inflector::slug(Yii::$app->request->post('name'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'alias' => $alias,
            'code' => 100,
        ];
    }

    public function actionIndex() {
        
        $app = $this->getApp();

        $model = new Categories;

        if (Yii::$app->request->get('parent_id') !== null) {
            $model->parent_id = Yii::$app->request->get('parent_id');
        }

        return $this->render('index', [
            'model'=> $model,
            'app' => $app,
        ]);
        
    }


    public function actionUpdate() {

        $app = $this->getApp();

        $model = $this->getCategory();

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'model' => $model->getAttributes(),
                ];
            }
            
            Yii::$app->getSession()->setFlash('success', Yii::t('zoo','Успешно'));
            return $this->redirect(['index','app'=>$app->id,'parent_id'=>$model->parent_id]);
        }

        return $this->render('update', [
            'app' => $app,
            'model'=> $model,
        ]);
    }
 
    public function actionDelete() {

        $app = $this->getApp();

        if (Yii::$app->request->isPost) {
  
            $category = Categories::findOne(Yii::$app->request->get('category'));

            if ($category !== null) {
                $category->delete();
            }
        }
        
        return $this->redirect(['index','app'=>$app->id]);
    }

    public function actionSort() {
        $request = Yii::$app->request;
        if($request->isPost) {

            if(($category = Categories::findOne($request->post('id'))) !== null) {
                $category->parent_id = $request->post('parent_id');
                $category->save();
            }

            $sort = $request->post('sort');

            if(count($sort)) {
                foreach ($sort as $key => $value) {
                    if(($category = Categories::findOne($key)) !== null) {
                        $category->sort = $value;
                        $category->save();
                    }
                }
            }
        }
    }


    public function getCategory($redirect = false) {
        
        $category_id = Yii::$app->request->get('category');

        $category = Categories::findOne($category_id);

        if ($category === null && $redirect) {
            Yii::$app->getSession()->setFlash('warning', Yii::t('zoo','Категория не существует'));
            return $this->redirect(['categories']);
        }
        elseif ($category === null) {
            return new Categories;
        }

        return $category;
    }


}