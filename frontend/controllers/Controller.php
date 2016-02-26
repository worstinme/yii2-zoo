<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\frontend\controllers;

use Yii;

use worstinme\zoo\frontend\models\Applications;

use yii\web\NotFoundHttpException;

class Controller extends \yii\web\Controller
{


    private $application;

    public function getApp() {

        if ($this->application === null) 

            if (($this->application = Applications::findOne(Yii::$app->request->get('app'))) === null)

                throw new NotFoundHttpException('The requested page does not exist.');
            
        return $this->application;
    }

}