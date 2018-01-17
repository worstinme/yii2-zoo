<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace worstinme\zoo\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;

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

    protected function processCatlist($categories, $parent_id = 0, $delimiter = null, $array = [])
    {
        if (count($categories)) {
            foreach ($categories as $key => $category) {
                if ($category['parent_id'] == $parent_id) {
                    $array[$category['id']] = (empty($delimiter) ? '' : $delimiter . ' ') . $category['name'];
                    unset($categories[$key]);
                    $array = $this->processCatlist($categories, $category['id'], $delimiter . '—', $array);
                }
            }
        }
        return $array;
    }
}
