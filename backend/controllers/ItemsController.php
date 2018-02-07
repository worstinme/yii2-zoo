<?php

namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\backend\models\Categories;
use worstinme\zoo\elements\system\Element;
use worstinme\zoo\widgets\ActiveForm;
use Yii;
use worstinme\zoo\backend\models\Items;
use worstinme\zoo\backend\models\ItemsSearch;
use worstinme\zoo\helpers\Inflector;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $searchModel = new ItemsSearch([
            'app_id' => $this->app->id,
        ]);

        $searchModel->regBehaviors();

        \Yii::beginProfile('Categories lists', 'zoo/backend');

        $categories = Categories::find()->where(['app_id' => $this->app->id])->orderBy('sort ASC')->all();

        $catlist = $this->processCatlist(ArrayHelper::toArray($categories, [
            Categories::className() => [
                'id',
                'parent_id',
                'name',
            ],
        ]));

        $parentCategories = array_filter($categories, function ($category) {
            return $category->parent_id == 0;
        });

        \Yii::endProfile('Categories lists', 'zoo/backend');

        /* if (!Yii::$app->user->can($this->module->adminAccessRoles)
                 && !Yii::$app->user->can('accessStrange')) {
             $searchModel->user_id = Yii::$app->user->identity->id;
         } */

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //print_r($searchModel->itemIds(Yii::$app->request->queryParams));

        if ($request->isPost) {

            $categoryIds = !empty($request->post('categoryIds')) ? explode(",", $request->post('categoryIds')) : [];
            $selection = !empty($request->post('selection')) ? explode(",", $request->post('selection')) : [];

            if ($request->post('replaceCategories') !== null && count($categoryIds) > 0) {

                $itemIds = count($selection) > 0 ? $selection : $searchModel->itemIds(Yii::$app->request->queryParams);

                if (is_array($itemIds) && count($itemIds)) {

                    foreach ($itemIds as $item_id) {

                        $rows = [];

                        foreach ($categoryIds as $category_id) {
                            $rows[] = [$item_id, (int)$category_id];
                        }

                        if (count($rows)) {

                            Yii::$app->db->createCommand()->delete('{{%items_categories}}', ['item_id' => $item_id])->execute();

                            Yii::$app->db->createCommand()
                                ->batchInsert('{{%items_categories}}', ['item_id', 'category_id'], $rows)
                                ->execute();

                        }


                    }

                }


                Yii::$app->session->setFlash('success', 'Обновлены категории для ' . count($itemIds) . ' материалов.');
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

                            Yii::$app->db->createCommand()->delete('{{%items_categories}}',
                                ['item_id' => $item_id, 'category_id' => $categoryIds])->execute();

                            Yii::$app->db->createCommand()
                                ->batchInsert('{{%items_categories}}', ['item_id', 'category_id'], $rows)
                                ->execute();

                        }


                    }
                }

                Yii::$app->session->setFlash('success', 'Добавлены категории для ' . count($itemIds) . ' материалов.');
            }

            if (Yii::$app->request->post('deleteCategories') !== null) {

                $itemIds = count($selection) > 0 ? $selection : $searchModel->itemIds(Yii::$app->request->queryParams);


                foreach ($itemIds as $item_id) {
                    Yii::$app->db->createCommand()->delete('{{%items_categories}}', ['item_id' => $item_id])->execute();
                }
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'catlist' => $catlist,
            'parentCategories' => $parentCategories,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($app, $id = null)
    {

        $app = $this->getApp($app);

        if ($id === null || ($model = Items::findOne(["id" => $id])) === null) {
            $model = new Items([
                'app_id' => $this->app->id,
            ]);
            $model->regBehaviors();
        }

        if (Yii::$app->request->post("reload") == 'true') {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model->load(Yii::$app->request->post());

            if ($model->validate() || true) {

                $renderedElements = Yii::$app->request->post("renderedElements", []);
                $renderElements = [];
                $removeElements = [];

                foreach ($model->renderedElements as $attribute) {

                    $element = $model->app->getElement($attribute);

                    if (!in_array($element, $renderedElements)) {
                        $renderElements[] = $element->attributeName;
                    } elseif ($element->refresh) {
                        $renderElements[] = $element->attributeName;
                        $removeElements[] = $element->attributeName;
                    } elseif ($element->related && $model->isAttributeChanged($element->related)) {
                        $renderElements[] = $element->attributeName;
                        $removeElements[] = $element->attributeName;
                    }
                }

                $renders = [];

                foreach ($renderElements as $element) {

                    $element = $model->app->getElement($element);

                    if (is_a($element, Element::className())) {
                        $path = '@worstinme/zoo/elements/system/' .$element->type . '/form.php';
                    } else {
                        $path = '@worstinme/zoo/elements/' .$element->type . '/form.php';
                    }

                    $form = new ActiveForm(['enableClientScript'=>false]);

                    $renders[$element->attributeName] = $form->field($model,$element->attributeName)->element()->renderAjax();

                   /* $renders[$element->attributeName] = $this->renderAjax($path, [
                        'model' => $model,
                        'element' => $element,
                        'form'=>new ActiveForm(),
                        'options'=>[],
                    ]); */
                }

                $removeElements = array_merge($removeElements, array_diff($renderedElements, $model->renderedElements));

                return [
                    'renderElements' => $renders,
                    'removeElements' => $removeElements,
                    'renderedElements' => $model->renderedElements,
                ];
            }

        } elseif (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'model' => $model->getAttributes(),
                ];
            } else {
                return [
                    'success' => false,
                    'model' => $model->getAttributes(),
                    'errors' => $model->errors,
                ];
            }
        } elseif ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->post('duplicate')) {
                $model->name = 'COPY ' . $model->name;
                $model->state = 0;
            }

            if ($model->save()) {

                if (Yii::$app->request->post('duplicate')) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('zoo', 'Материал скопирован и сохранён'));
                } else {
                    Yii::$app->getSession()->setFlash('success', Yii::t('zoo', 'Материал сохранён'));
                }


                if (Yii::$app->request->post('save') == 'close') {
                    return $this->redirect(['index', 'app' => $app->id]);
                } else {
                    return $this->redirect(['create', 'app' => $app->id, 'id' => $model->id]);
                }
            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);

    }

    public function actionAliasCreate()
    {
        $alias = Inflector::slug(Yii::$app->request->post('name'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'alias' => $alias,
            'code' => 100,
        ];
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('zoo', 'ITEM_REMOVED_SUCCESS'));

        return $this->redirect(['index', 'app' => $model->app_id]);
    }

    protected function findModel($id)
    {
        if (($model = Items::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
