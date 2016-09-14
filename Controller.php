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

    public function init() {

        if (($this->app = Applications::find()->where(['name'=>$this->id])->one()) === null) {
            throw new NotFoundHttpException('The application named "'.$this->id.'" does not exist.');
        }

        $this->app->lang = substr(Yii::$app->request->get('lang',Yii::$app->language), 0, 2);

        Yii::trace('Language: '.$this->app->lang);

        parent::init();
    }

    public function beforeAction($action)
    {
        if ($action->id == 'index') {
            Yii::trace('Change action: '.$action->id);
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true; 
    }

    public function actionA($a)
    {
    	if (($category = Categories::find()->where(['alias'=>$a,'app_id'=>$this->app->id])->one()) !== null) {
            // материал дефолтного приложения по алиасу
            return $this->renderCategory($category);
        }
        elseif (($model = $this->app->getItems()->where([Items::tablename().'.alias'=>$a])->orWhere([Items::tablename().'.id'=>$a])->one()) !== null) {
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

    public function actionIndex($app = null) {

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
            'app'=>$this->app,
        ]);
    }

    protected function renderCategory($category) {

        $app = $category->app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();
        $searchModel->category = [$category->id];

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('category',[
            'category'=>$category,
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}
