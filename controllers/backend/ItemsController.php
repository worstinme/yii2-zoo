<?php

namespace worstinme\zoo\controllers\backend;

use Yii;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\ItemsSearch;
use worstinme\zoo\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use worstinme\uikit\ActiveForm;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex()
    {

        $app = $this->getApplication(true);

        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'app'=>$app,
        ]);
    }

    /**
     * Displays a single Items model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $app = $this->getApplication(true);
        
        $model = new Items();

        if (Yii::$app->request->post("reload") == 'true') {

            if ($model->load(Yii::$app->request->post()) && $model->validate() || true) {
                $fields = Yii::$app->request->post('Field');

                foreach ($model->fields as $key=>$field) {
                    $field->{$field->name} = $fields[$field->name];
                    $field->reload = true;
                    $field->validate();                    
                }

            }

            $rendered_fields_ids = Yii::$app->request->post("rendered_fields_ids",[]);
            $new_fields_ids = [];
            $new_fields_renders = [];

            if (count($model->fields)) {
                foreach ($model->fields as $id=>$field) {                    
                    $new_fields_ids[] = $id;
                    if (!in_array($id, $rendered_fields_ids)) {
                        $new_fields_renders[$id] = $this->renderAjax('@worstinme/zoo/fields/'.$field->type.'/_form.php', [
                                'model'=>$model,
                                'app'=>$app,
                                'field'=>$field,
                                'id'=>$id,
                            ]);
                    }
                }
            }

            $fields_to_remove = array_diff($rendered_fields_ids, $new_fields_ids);             

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            return [
                'new_fields_renders' => $new_fields_renders,
                'new_fields_ids' => $new_fields_ids,
                'fields_to_remove'=> $fields_to_remove,
            ];

        }

        if (Yii::$app->request->isPost) { 
            if ($model->load(Yii::$app->request->post()) && $model->validate() || true) {
                $fields = Yii::$app->request->post('Field');
                foreach ($model->fields as $key=>$field) {
                    $field->{$field->name} = $fields[$field->name];

                        $field->reload = true;
                    $field->validate();                    
                }
            }
        }

        return $this->render('create',[
            'app'=>$app,
            'model'=>$model,
        ]); 
        
    } 

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Items model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
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
