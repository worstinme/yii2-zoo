<?php
namespace worstinme\zoo;

use Yii;

use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\S as s;

use yii\web\NotFoundHttpException;


class Controller extends \yii\web\Controller
{

    public $app;

    public function init()
    {
        parent::init();

        if (!Yii::$app->has('zoo')) {
            Yii::$app->set('zoo',[ 'class'=>'\worstinme\zoo\Zoo' ]);
        }  
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (($this->app = Applications::find()->where(['name'=>$this->id])->one()) === null) {
            throw new NotFoundHttpException('The application does named "'.$this->id.'"not exist.');
        }

        return true; 
    }

    public function actionIndex()
    {
    	return $this->renderApplication();
    }

    public function actionA($a)
    {
    	if (($category = Categories::find()->where(['alias'=>$a,'app_id'=>$this->app->id])->one()) !== null) {
            // материал дефолтного приложения по алиасу
            return $this->renderCategory($category);
        }
        elseif (($model = $this->app->getItems()->where(['i.alias'=>$a])->orWhere(['i.id'=>$a])->one()) !== null) {
            // материал дефолтного приложения по алиасу
            return $this->renderItem($model);
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAb($a,$b)
    {
        if (($parent_cat = Categories::find()->where(['app_id'=>$this->app->id,'alias'=>$a])->one()) !== null) {
            // приложение -> категория по алиасу
            if (($category = Categories::find()->where(['app_id'=>$this->app->id,'parent_id'=>$parent_cat->id,'alias'=>$b])->one()) !== null) {
                
                return $this->renderCategory($category);
            }
            elseif(($model = Items::find()->where(['app_id'=>$this->app->id,'alias'=>$b])->one()) !== null) {
                // приложение -> материал по алиасу
                return $this->renderItem($model);
            }
            elseif(($model = Items::find()->where(['app_id'=>$this->app->id,'id'=>$b])->one()) !== null) {
                // приложение -> материал по ID
                return $this->renderItem($model);
            }  
        }       

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAbc($a,$b,$c)
    {
        if (($app = Applications::find()->where(['name'=>$a])->one()) !== null) {

            if (($parent_cat = Categories::find()->where(['app_id'=>$app->id,'alias'=>$b])->one()) !== null) {
                // приложение -> категория по алиасу
                if (($category = Categories::find()->where(['app_id'=>$app->id,'parent_id'=>$parent_cat->id,'alias'=>$c])->one()) !== null) {
                    
                    return $this->renderCategory($category);
                }
                elseif(($model = Items::find()->where(['app_id'=>$app->id,'alias'=>$c])->one()) !== null) {
                    // приложение -> материал по алиасу
                    return $this->renderItem($model);
                }
                elseif(($model = Items::find()->where(['app_id'=>$app->id,'id'=>$c])->one()) !== null) {
                    // приложение -> материал по ID
                    return $this->renderItem($model);
                }  
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

    public function actionSearch() {

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams); 

        return $this->render('search', [
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    protected function renderApplication($app = null) {

        $app = $app === null ? $this->app : $app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams); 

        return $this->render('application', [
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function renderItem($model) {

        return $this->render('item',[
            'model'=>$model,
        ]);
    }

    protected function renderCategory($category) {

        $app = $category->app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();
        $searchModel->setAttribute('categories.id',[$category->id]);

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('category',[
            'category'=>$category,
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}
