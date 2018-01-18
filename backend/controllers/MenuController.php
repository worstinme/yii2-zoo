<?php

namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\backend\models\Categories;
use worstinme\zoo\backend\models\Items;
use Yii;
use worstinme\zoo\backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuController extends Controller
{
    public $subnav = false;

    public function actionIndex()
    {
        $group = ArrayHelper::index(Menu::find()->where(['parent_id' => null])->orderBy('sort')->all(), null, ['menu', 'lang']);

        return $this->render('index', [
            'group' => $group,
        ]);
    }

    public function actionUpdate($id = null)
    {
        if ($id === null) {
            $model = new Menu([
                'lang' => Yii::$app->language,
            ]);
        } else {
            $model = $this->findModel($id);
        }

        if (Yii::$app->request->post('reload')) {
            $model->load(Yii::$app->request->post());
        } elseif ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $applications = ArrayHelper::map(Yii::$app->zoo->applications,'id','title');

        $categories = Categories::find()->where(['app_id' => $model->application, 'lang' => $model->lang])->orderBy('sort ASC')->all();

        $categories = $this->processCatlist(ArrayHelper::toArray($categories, [
            Categories::className() => [
                'id',
                'parent_id',
                'name',
            ],
        ]));

        $items = Items::find()
            ->from(['i'=>Items::tableName()])
            ->where(['app_id' => $model->application, 'lang' => $model->lang])
            ->leftJoin(['c'=>'{{%items_categories}}'],'i.id = c.item_id')
            ->andFilterWhere(['c.category_id'=>$model->category])
            ->all();

        $items = ArrayHelper::map($items, 'id','name');

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'applications'=>$applications,
            'items'=>$items,
        ]);
    }

    public function actionSort() {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $sort = Yii::$app->request->post('sort');

        if (is_array($sort)) {
            foreach ($sort as $id => $index) {
                if (($item = Menu::findOne($id)) !== null) {
                    $item->updateAttributes(['sort'=>$index]);
                }
            }
            return [
                'message'=>Yii::t('zoo','SORTING_SUCCESSEFUL_MESSAGE'),
            ];
        }

        return [
            'message'=>Yii::t('zoo','SORTING_FAILED_MESSAGE'),
        ];
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}