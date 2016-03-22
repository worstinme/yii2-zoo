<?php

namespace worstinme\zoo\backend\controllers;

use Yii;

use worstinme\zoo\backend\models\Items;
use worstinme\zoo\backend\models\ItemsSearch;

use yii\web\NotFoundHttpException;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{

    public function actionIndex()
    {
        $app = $this->getApp();
        
        $searchModel = new ItemsSearch();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {

            $categoryIds = Yii::$app->request->post('categoryIds',[]);
            $selection = Yii::$app->request->post('selection',[]);
            
            if (Yii::$app->request->post('replaceCategories') !== null && count($categoryIds) > 0) {

                $itemIds = count($selection) > 0 ? $selection : $searchModel->itemIds(Yii::$app->request->queryParams);

                foreach ($itemIds as $item_id) {
                    
                    Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$item_id])->execute();

                    $rows = [];

                    foreach ($categoryIds as $category_id) {
                        $rows[] = [$item_id, (int)$category_id];
                    }

                    if (count($rows)) {

                        Yii::$app->db->createCommand()
                            ->batchInsert('{{%zoo_items_categories}}', ['item_id', 'category_id'], $rows)
                            ->execute();

                    }

                }


                Yii::$app->session->setFlash('success', 'Обновлены категории для '.count($itemIds).' материалов.');
            }

            if (Yii::$app->request->post('removeCategories') !== null ) {

                $itemIds = count($selection) > 0 ? $selection : $searchModel->itemIds(Yii::$app->request->queryParams);
                
                foreach ($itemIds as $item_id) {
                    Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$item_id])->execute();
                }
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($app, $id = null)
    {
        if ($id === null) {
            $model = new Items();
            $model->name = 'Заголовок';
            $model->app_id = $this->app->id;
            $model->save();

            return $this->redirect(['create','id'=>$model->id,'app'=>$model->app_id]);
            
        }
        else {
            $model = Items::findOne($id);
        }   


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
                    $path = '@worstinme/zoo/elements/'.$model->elements[$element]['type'].'/_form.php';
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
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Материал сохранён'));
        }

        return $this->render('create',[
            'model'=>$model,
        ]); 
        
    } 

    public function actionView($id)
    {
        
        $model = Items::findOne($id);

        return $this->render('view',[
            'model'=>$model,
        ]); 

    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index','app'=>$model->app_id]);
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
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
