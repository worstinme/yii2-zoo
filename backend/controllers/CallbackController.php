<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;

class CallbackController extends Controller
{
    public function actions() {

        $actions = [];

        foreach ($this->module->elements as $element => $name) {

            if (is_file(Yii::getAlias('@worstinme/zoo/elements').DIRECTORY_SEPARATOR.$element.DIRECTORY_SEPARATOR.'CallbackAction.php')) {
                $actions[$element] = 'worstinme\zoo\elements\\'.$element.'\CallbackAction';
            }
        }

        return $actions;

    }
}