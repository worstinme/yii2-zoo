<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\controllers\backend;

use worstinme\zoo\models\Applications;
use worstinme\zoo\models\ItemsSearch;
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\Fields;
use worstinme\zoo\models\Items;

use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

class DefaultController extends \worstinme\zoo\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['elfinder','index','application','config','update-category','templates','fields','categories'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
    	$model = new Applications;
        $applications = Applications::find()->all();

        return $this->render('index',[
                'applications'=>$applications,
                'model'=>$model,
            ]);
    }

    // список приложений

    public function actionApplication() {
        $app = $this->getApplication(true);
        return $this->redirect(['/'.$this->module->id.'/items/index','app'=>$app->id]);
    }

    // редактирование/создание приложения

    public function actionUpdate()
    {
        $model = $this->application;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Успешно'));
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    // список категорий

    public function actionCategories() {
        
        $app = $this->getApplication(true);

        $model = new Categories;

        return $this->render('categories', [
            'app' => $app,
            'model'=> $model,
        ]);
        
    }

    public function actionConfig() {
        
        $app = $this->getApplication(true);

        if ($app->load(Yii::$app->request->post()) && $app->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Настройки сохранены'));
        }

        return $this->render('config', [
            'app' => $app,
        ]);
        
    }

    // редактирование/создание категории

    public function actionUpdateCategory() {

        $app = $this->getApplication(true);

        $model = $this->category;

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Успешно'));
            return $this->redirect(['categories','app'=>$app->id]);
        }

        return $this->render('updateCategory', [
            'app' => $app,
            'model'=> $model,
        ]);
    }

    public function actionUpdateType() {

        $app = $this->getApplication(true);

        $model = $this->category;

        $model->app_id = $app->id;
        $model->parent_id = 0;
        $model->type = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Успешно'));
        }

        return $this->render('updateType', [
            'app' => $app,
            'model'=> $model,
        ]);
    }


    public function actionFields() {
        
        $app = $this->getApplication(true);

        return $this->render('fields/index', [
            'app' => $app,
        ]);

    }

    // редактирование/создание полей

    public function actionCreateField() {

        $app = $this->getApplication(true);

        $model = new Fields;

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Элемент создан. Необходимо его настроить.'));
            $this->redirect(['update-field','app'=>$app->id,'field'=>$model->id]);
        }

        return $this->render('fields/create', [
            'app' => $app,
            'model'=> $model,
        ]);
    }

    public function actionUpdateField() {

        $app = $this->getApplication(true);

        if (($model = Fields::findOne(Yii::$app->request->get('field'))) === null) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Элемент не найден'));
            $this->redirect(['fields','app'=>$app->id]);
        }

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Настройка элемента сохранены'));
           // $this->redirect(['fields','app'=>$app->id]);
        }

        return $this->render('fields/update', [
            'app' => $app,
            'model'=> $model,
        ]);
    }

    public function actionDeleteField($field) {

        $app = $this->getApplication(true);

        if (Yii::$app->request->isPost) {
            if (($fi= Fields::findOne($field)) !== null) {
                $fi->delete();
            }
        }

        return $this->redirect(['fields','app'=>$app->id]);
    }

    public function actionTemplates() {
        
        $app = $this->getApplication(true);

        return $this->render('templates', [
            'app' => $app,
        ]);

    }

    public function actionTemplate() {
        $request = Yii::$app->request;
        $app = $this->getApplication();

        if($request->isPost) {   
            $app->setTemplate(Yii::$app->request->post('template'),Yii::$app->request->post('rows'));
            $app->save();
            echo 'шаблон сохранен';
        }
    }

    // save category sort
    public function actionCategorySort() {
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

    public function actionElfinder() {
        return $this->render('elfinder', [
        ]);
    }

    public function actionAliasCreate() {
        $request = Yii::$app->request;
        $alias = $request->post('alias');
        $nodelimiter = $request->post('nodelimiter');

        if ($request->isPost && !empty($alias)) {
            
            $d = [];
            $str = explode(" ",$alias);
            if (count($str)) {
                foreach ($str as $s) {
                    $d[] = $this->transliteration($s);
                }
            }

            $string = implode('-',$d);

            if ($nodelimiter == true) {
                $string = str_replace("-", "", $string);
            }
            
            echo $string;

            //echo \yii\helpers\Inflector::slug($alias);
        }
        else echo '';
    }

    public function getCategory($redirect = false) {
        
        $category_id = Yii::$app->request->get('category');

        $category = Categories::findOne($category_id);

        if ($category === null && $redirect) {
            Yii::$app->getSession()->setFlash('warning', Yii::t('admin','Категория не существует'));
            return $this->redirect(['categories']);
        }
        elseif ($category === null) {
            return new Categories;
        }

        return $category;
    }

    public function getField() {

        if (($model = Fields::findOne(Yii::$app->request->get('field_id'))) === null) {
            $model = new Fields;
        }
        
        return $model;       

    }



}