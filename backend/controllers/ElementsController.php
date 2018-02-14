<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\backend\models\Categories;
use worstinme\zoo\elements\BaseElement;
use Yii;
use worstinme\zoo\backend\models\Elements;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ElementsController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'delete'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $app = $this->getApp();

        return $this->render('index', [
            'app' => $app,
        ]);

    }

    // редактирование/создание полей

    public function actionCreate()
    {

        $app = $this->getApp();

        $model = new BaseElement([
            'app_id' => $app->id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('zoo', 'ELEMENTS_CREATION_SUCCESS'));
            return $this->redirect(['update', 'element' => $model->id, 'app' => $model->app_id]);
        }

        $elements = [];

        foreach (Yii::$app->zoo->elementsPaths as $namespace => $path) {

            $path = rtrim(Yii::getAlias($path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $directories = glob($path . '*', GLOB_ONLYDIR);

            foreach ($directories as $directory) {

                $explode = explode(DIRECTORY_SEPARATOR, $directory);
                $elementName = array_pop($explode);

                if (class_exists($namespace . '\\' . $elementName . '\Element')) {
                    $elements[] = $elementName;
                }

            }

        }

        return $this->render('create', [
            'app' => $app,
            'elements' => $elements,
            'model' => $model,
        ]);
    }

    public function actionUpdate($element)
    {

        $app = $this->getApp();

        $model = $this->getElement($element);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('zoo', 'ELEMENTS_UPDATE_SUCCESS'));
        }

        $categories = Categories::find()->where(['app_id' => $this->app->id])->orderBy('sort ASC')->all();

        $catlist = \worstinme\zoo\helpers\CategoriesHelper::processCatlist(ArrayHelper::toArray($categories, [
            Categories::className() => [
                'id',
                'parent_id',
                'name',
            ],
        ]));

        return $this->render('update', [
            'app' => $app,
            'model' => $model,
            'catlist'=> $catlist,
        ]);
    }

    public function actionSort() {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $sort = Yii::$app->request->post('sort');

        if (is_array($sort)) {
            foreach ($sort as $id => $index) {
                if (($item = BaseElement::findOne($id)) !== null) {
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

    public function actionDelete($element)
    {

        $app = $this->getApp();

        if (Yii::$app->request->isPost) {
            $this->getElement($element)->delete();
        }

        return $this->redirect(['index', 'app' => $app->id]);
    }

    protected function getElement($id)
    {
        if (($model = BaseElement::findOne($id)) !== null) {
            return $model;
        }
        throw  new NotFoundHttpException('Element not found');
    }

}