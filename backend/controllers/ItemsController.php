<?php

namespace worstinme\zoo\backend\controllers;

use Yii;

use worstinme\zoo\models\Items;
use worstinme\zoo\backend\models\ItemsSearch;

use yii\web\NotFoundHttpException;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'delete'],
                        'roles' => $this->module->accessRoles !== null ? $this->module->accessRoles : ['admin','moder'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','delete'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $app = $this->getApp();


        $request = Yii::$app->request;
        
        $searchModel = new ItemsSearch();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();

        if ($this->module->accessRoles === null && !Yii::$app->user->can('admin') 
                && !Yii::$app->user->can('accessStrange')) {
            $searchModel->user_id = Yii::$app->user->identity->id;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //print_r($searchModel->itemIds(Yii::$app->request->queryParams));

        if ($request->isPost) {

            $categoryIds = !empty($request->post('categoryIds')) ? explode(",",$request->post('categoryIds')) : [];
            $selection = !empty($request->post('selection')) ? explode(",",$request->post('selection')) : [];
            
            if ($request->post('replaceCategories') !== null && count($categoryIds) > 0) {

                $itemIds = count($selection) > 0 ? $selection : $searchModel->itemIds(Yii::$app->request->queryParams);

                if (is_array($itemIds) && count($itemIds)) {
                   
                    foreach ($itemIds as $item_id) {
                        
                        $rows = [];

                        foreach ($categoryIds as $category_id) {
                            $rows[] = [$item_id, (int)$category_id];
                        }

                        if (count($rows)) {

                            Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$item_id])->execute();

                            Yii::$app->db->createCommand()
                                ->batchInsert('{{%zoo_items_categories}}', ['item_id', 'category_id'], $rows)
                                ->execute(); 

                        }


                    } 

                }


                Yii::$app->session->setFlash('success', 'Обновлены категории для '.count($itemIds).' материалов.');
            }

            if ($request->post('addCategories') !== null && count($categoryIds) > 0) {
                $itemIds = count($selection) > 0 ? $selection : $searchModel->itemIds(Yii::$app->request->queryParams);
                if (is_array($itemIds) && count($itemIds)) {
                    foreach ($itemIds as $item_id) {
                        
                        $rows = [];

                        foreach ($categoryIds as $category_id) {
                            $rows[] = [$item_id, (int)$category_id];
                        }

                        if (count($rows)) {

                            Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', 
                                ['item_id'=>$item_id,'category_id'=>$categoryIds])->execute();

                            Yii::$app->db->createCommand()
                                ->batchInsert('{{%zoo_items_categories}}', ['item_id', 'category_id'], $rows)
                                ->execute(); 

                        }


                    } 
                }

                Yii::$app->session->setFlash('success', 'Добавлены категории для '.count($itemIds).' материалов.');
            }

            if (Yii::$app->request->post('deleteCategories') !== null ) {

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

        $app = $this->getApp($app);

        if ($id === null || ($model = Items::findOne(["id"=>$id])) === null) {
            $model = new Items();
            $model->app_id = $app->id;
            $model->regBehaviors();
        }
        else {
            if ($this->module->accessRoles === null && !Yii::$app->user->can('admin') && !Yii::$app->user->can('accessStrange') && $model->user_id !== Yii::$app->user->identity->id) {
                Yii::$app->getSession()->setFlash('warning', Yii::t('backend','Доступ запрещён'));
                return $this->redirect(['index','app'=>$app->id]);
            }
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
                    elseif($model->elements[$element]->refresh) {
                        $renderElements[] = $element;
                        $removeElements[] = $element;
                    }   
                }

                $renders = [];

                foreach ($renderElements as $element) {
                    $path = '@worstinme/zoo/elements/'.$model->elements[$element]['type'].'/form.php';
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
        elseif (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'model' => $model->getAttributes(),
                ];
            }
            else {
                return [
                    'success' => false,
                    'model' => $model->getAttributes(),
                    'errors'=>$model->errors,
                ];
            }
        }
        elseif($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend','Материал сохранён'));
            return $this->redirect(['index','app'=>$app->id]);
        }


        return $this->render('create',[
            'model'=>$model,
        ]); 
        
    } 

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (($this->module->accessRoles === null && !Yii::$app->user->can('admin'))  && !Yii::$app->user->can('accessStrange') && Yii::$app->user->identity->id != $model->user_id) {
            Yii::$app->getSession()->setFlash('warning', Yii::t('backend','Удаление не разрешено'));
            return $this->redirect(['index','app'=>$model->app_id]);
        }

        $model->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('backend','Материал удалён, окончательно и безповоротно. Совсем. Не вернуть его уже.'));

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
        if (($model = Items::find()->where(['id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
