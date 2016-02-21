<?php

namespace worstinme\zoo\controllers\backend;

use Yii;
use worstinme\zoo\models\Item;
use worstinme\zoo\models\ItemsSearch;
use worstinme\zoo\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        
        $model = new Item();

        $model->app_id = $this->app->id;

        $model->registerElements();

        $model->attachBehaviors();

        if (Yii::$app->request->post("reload") == 'true') {
            if ($model->load(Yii::$app->request->post()) && $model->validate() || true) {
                $renderedElements = Yii::$app->request->post("renderedElements",[]);
                $renderElements = [];
                $removeElements = [];

                foreach ($model->renderedElements as $element) {
                    if (!in_array($element,$renderedElements)) {
                        $renderElements[] = $element;
                    }
                    elseif($model->getElementParam($element,'refresh',false)) {
                        $renderElements[] = $element;
                        $removeElements[] = $element;
                    }   
                }

                $renders = [];

                foreach ($renderElements as $element) {
                    $path = '@worstinme/zoo/fields/'.$model->elements[$element]['type'].'/_form.php';
                    $renders[$element] = $this->renderAjax($path,[
                                'model'=>$model,
                                'attribute'=>$element,
                            ]);
                }

                $removeElements = array_merge($removeElements, array_diff($renderedElements, $model->renderedElements)); 

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
                return [
                    'renderElements' => $renders,
                    'removeElements' => $removeElements,
                    'renderedElements'=> $model->renderedElements,
                ];
            }
        }
        elseif ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$this->redirect(['view','id'=>$model->id]);
        }

        return $this->render('create',[
            'model'=>$model,
        ]); 
        
    } 

    public function actionView($id)
    {
        
        $model = Item::findOne($id);

        return $this->render('view',[
            'model'=>$model,
        ]); 

    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
