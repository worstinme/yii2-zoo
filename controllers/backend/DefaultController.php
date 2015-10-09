<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\controllers\backend;

use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Categories;

class DefaultController extends \worstinme\zoo\Controller
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

    public function actionApplication() {
        
        $app = $this->getApplication(true);

        return $this->render('application', [
            'app' => $app,
        ]);
    }

    public function actionCategories() {
        
        $app = $this->getApplication(true);

        $model = new Categories;

        return $this->render('categories', [
            'app' => $app,
            'model'=> $model,
        ]);
        
    }

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

    public function actionUpdateCategory() {

        $app = $this->getApplication(true);

        $model = $this->category;

        $model->app_id = $app->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Успешно'));
            return $this->redirect(['categories', 'id' => $model->id]);
        }

        return $this->render('updateCategory', [
            'app' => $app,
            'model'=> $model,
        ]);
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

    public function actionAliasCreate() {
        $request = Yii::$app->request;
        $alias = $request->post('alias');

        if ($request->isPost && !empty($alias)) {
            $d = [];
            $str = explode(" ",$alias);
            if (count($str)) {
                foreach ($str as $s) {
                    $d[] = $this->transliteration($s);
                }
            }
            echo implode('-',$d);
            //echo \yii\helpers\Inflector::slug($alias);
        }
        else echo '';
    }

    public function getApplication($redirect = false) {
        
        $app = Yii::$app->request->get('app');

        $application = Applications::findOne($app);

        if ($application === null) {
            $application = Applications::find()->where(['name'=>$app])->one();
        }

        if ($application === null && $redirect) {
            Yii::$app->getSession()->setFlash('warning', Yii::t('admin','Приложение не существует'));
            return $this->redirect(['index']);
        }
        elseif($application === null) {
            return new Applications;
        }

        return $application;
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

}