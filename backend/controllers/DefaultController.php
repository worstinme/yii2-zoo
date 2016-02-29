<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\backend\models\Applications;
use worstinme\zoo\backend\models\Categories;
use worstinme\zoo\backend\models\Elements;
use worstinme\zoo\backend\models\Items;

use yii\web\NotFoundHttpException;
use Yii;

class DefaultController extends Controller
{

    public function actionIndex()
    {
    	$model = new Applications;

        $applications = Applications::find()->all();

        return $this->render('index',[
                'applications'=>$applications,
                'model'=>$model,
            ]);
    }

    // редактирование/создание приложения

    public function actionUpdate()
    {
        $model = new Applications;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Успешно'));
            return $this->redirect(['index']);
        } else {
            print_r($model->errors);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    // список категорий

    public function actionCategories() {
        
        $app = $this->getApp();

        $model = new Categories;

        if (Yii::$app->request->get('parent_id') !== null) {
            $model->parent_id = Yii::$app->request->get('parent_id');
        }

        return $this->render('categories', [
            'model'=> $model,
            'app' => $app,
        ]);
        
    }

    public function actionConfig() {

        $app = $this->getApp();
    
        if ($app->load(Yii::$app->request->post()) && $app->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Настройки сохранены'));
        }

        return $this->render('config', [
            'app' => $app,
        ]);
        
    }

    // редактирование/создание категории

    public function actionUpdateCategory() {

        $app = $this->getApp();

        $model = $this->category;

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Успешно'));
            return $this->redirect(['categories','app'=>$app->id,'parent_id'=>$model->parent_id]);
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
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Успешно'));
        }

        return $this->render('updateType', [
            'app' => $app,
            'model'=> $model,
        ]);
    }


    public function actionElements() {
        
        $app = $this->getApp();

        return $this->render('elements/index', [
            'app' => $app,
        ]);

    }

    // редактирование/создание полей

    public function actionCreateElement() {

        $app = $this->getApp();

        $model = new Elements;

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Элемент создан. Необходимо его настроить.'));
            $this->redirect(['update-element','app'=>$app->id,'element'=>$model->id]);
        }

        return $this->render('elements/create', [
            'app' => $app,
            'model'=> $model,
        ]);
    }

    public function actionUpdateElement() {

        $app = $this->getApp();

        if (($model = elements::findOne(Yii::$app->request->get('element'))) === null) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Элемент не найден'));
            $this->redirect(['elements','app'=>$app->id]);
        }

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Настройка элемента сохранены'));
           // $this->redirect(['elements','app'=>$app->id]);
        }

        return $this->render('elements/update', [
            'app' => $app,
            'model'=> $model,
        ]);
    }

    public function actionDeleteElement($element) {

        $app = $this->getApp();

        if (Yii::$app->request->isPost) {
            if (($fi= elements::findOne($element)) !== null) {
                $fi->delete();
            }
        }

        return $this->redirect(['elements','app'=>$app->id]);
    }

    public function actionTemplates() {
        
        $app = $this->getApp();

        $templates = $this->module->itemTemplates;

        $elements = $app->elements;

        $rows = null;

        $at = Yii::$app->request->get('template');

        if (($item=Items::findOne(Yii::$app->request->get('item'))) !== null && $at !== null) {
            $rows  = $item->getTemplate($at);
        }
        elseif ($at !== null) {
            $rows  = $app->getTemplate($at);
        }
        
        return $this->render('templates', [
            'app' => $app,
            'elements'=>$elements,
            'rows'=>$rows,
            'templates'=>$templates,
            'item'=>$item,
            'at'=>$at,
        ]);

    }

    public function actionTemplate() {

        $request = Yii::$app->request;

        $app = $this->getApp();

        if($request->isPost) {  

            $rows = Yii::$app->request->post('rows');

            $name = Yii::$app->request->post('template');

            if (($item=Items::findOne(Yii::$app->request->get('item'))) !== null) {

                $params = \yii\helpers\Json::decode($item->params);

                foreach ($rows as $key=>$row) {
                    if (!count($row['items'])) {
                        unset($rows[$key]);
                    }
                }
                if (is_array($params['templates']))  $params['templates'][$name] = $rows;
                else $params['templates'] = [$name => $rows];

                Yii::$app->db->createCommand()->update('{{%zoo_items}}', ['params' => \yii\helpers\Json::encode($params)], ['id'=>$item->id])->execute();

            }
            else {
                $app->setTemplate($name,$rows);
                $app->save();
            }        

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
            Yii::$app->getSession()->setFlash('warning', Yii::t('backend','Категория не существует'));
            return $this->redirect(['categories']);
        }
        elseif ($category === null) {
            return new Categories;
        }

        return $category;
    }

    public function getElement() {

        if (($model = elements::findOne(Yii::$app->request->get('element_id'))) === null) {
            $model = new elements;
        }
        
        return $model;       

    }



}