<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;

class ElfinderController extends Controller
{
    public $subnav = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => Yii::$app->zoo->adminAccessRoles,
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {

        return $this->render('index');

    }
}