<?php

namespace worstinme\zoo\frontend\controllers;

use Yii;
use worstinme\zoo\frontend\models\Items;
use worstinme\zoo\frontend\models\Categories;
use worstinme\zoo\frontend\models\ItemsSearch;
use worstinme\zoo\frontend\models\S as s;
use worstinme\zoo\frontend\models\Applications;

use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    private $application;

    public function actionFrontpage()
    {
    	if (($app = Applications::findOne(1)) !== null) {
            return $this->renderApplication($app);
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionA($a)
    {
    	if (($app = Applications::find()->where(['name'=>$a])->one()) !== null) {
            // приложение по алиасу
            return $this->renderApplication($app);
        }
        elseif (($model = Items::find()->where(['alias'=>$a,'app_id'=>1])->one()) !== null) {
            // материал дефолтного приложения по алиасу
            return $this->renderItem($model);
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAb($a,$b)
    {
        if (($app = Applications::find()->where(['name'=>$a])->one()) !== null) {

            if (($category = Categories::find()->where(['app_id'=>$app->id,'alias'=>$b])->one()) !== null) {
                // приложение -> категория по алиасу
                return $this->renderCategory($category);
            }
            elseif(($model = Items::find()->where(['app_id'=>$app->id,'alias'=>$b])->one()) !== null) {
                // приложение -> материал по алиасу
                return $this->renderItem($model);
            }
            elseif(($model = Items::find()->where(['app_id'=>$app->id,'id'=>$b])->one()) !== null) {
                // приложение -> материал по ID
                return $this->renderItem($model);
            }
        }
        else {

            if (($category = Categories::find()->where(['app_id'=>1,'alias'=>$a])->one()) !== null) {
                if(($model = Items::find()->where(['app_id'=>1,'alias'=>$b])->one()) !== null && $model->parentCategory->alias == $a) {
                    // приложение -> материал по алиасу
                    return $this->renderItem($model);
                }
                elseif(($model = Items::find()->where(['app_id'=>1,'id'=>$b])->one()) !== null && $model->parentCategory->alias == $a) {
                    // приложение -> материал по ID
                    return $this->renderItem($model);
                }
            }
            // категория дефолтного приложения -> материал
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function getApp() {

        if ($this->application === null) {
            
            $app = Yii::$app->request->get('app');

            $application = Applications::find()->where(['name'=>$app])->one();

            if($application === null && Yii::$app->controller->action->id == 'frontpage') {
                $page_id = Yii::$app->request->get('page_id');
                if (($page = Items::findOne($page_id)) !== null) {
                    $application = Applications::find()->where(['id'=>$page->app_id])->one();
                }
            }

            if ($application === null) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            $this->application = $application;
        }
        
        return $this->application;
    }

    protected function renderApplication($app) {

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->rend('application',$app, [
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function renderItem($model) {

        return $this->rend('item',$model->app,[
            'model'=>$model,
        ]);
    }
}
