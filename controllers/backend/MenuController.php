<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\controllers\backend;

use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Menu;

use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

class MenuController extends \worstinme\zoo\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','update'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate($menu = null)
    {


        if ($menu === null) {
            $model = new Menu;
        }
        else {
            $model = Menu::findOne($menu);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('admin','Настройки сохранены'));
        }

        return $this->render('update',[
            'model'=>$model
        ]);
    }

}