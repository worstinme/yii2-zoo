<?php

namespace worstinme\zoo\frontend\controllers;

use Yii;
use worstinme\zoo\frontend\models\Items;
use worstinme\zoo\frontend\models\Applications;

use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    private $application;

    public function actionFrontpage($page_id)
    {

    	if (($page = Items::findOne($page_id)) === null) {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

        return $this->render('page',[
        	'page'=>$page,
        ]);
    }

    public function actionA($a)
    {

    	if (($app = Applications::find()->where(['name'=>$a])->one()) === null) {
    		
    		if (($page = Items::find()->where(['alias'=>$a,'app_id'=>1])->one()) === null) {


    			throw new NotFoundHttpException('The requested page does not exist.');
    		}
    		
    		return $this->render('page',[
		    	'page'=>$page,
		    ]);
    		
    	}

        return $this->render('application',[
        	'app'=>$app,
        ]);

    }


    public function getApplication($redirect = false) {
        
        

        return $application;
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
}
