<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\Application;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property Application $app
 */

class Controller extends \yii\web\Controller
{
    private $application;
    public $subnav = true;

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

    public function getApp()
    {
        return Yii::$app->zoo->getApplication(Yii::$app->request->get('app'));
    }

    public function afterAction($action, $result)
    {
        Yii::$app->getUser()->setReturnUrl(Yii::$app->request->url);
        return parent::afterAction($action, $result);
    }
}
