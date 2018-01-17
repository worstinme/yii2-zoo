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

        $elements = [];

        foreach ($this->app->elements as $element) {
            if (!in_array($element->type, $elements)) {
                $elements[] = $element->type;
                if (is_file(Yii::getAlias('@worstinme/zoo/elements').DIRECTORY_SEPARATOR.$element->type.DIRECTORY_SEPARATOR.'CallbackAction.php')) {
                    $actions[$element->type] = 'worstinme\zoo\elements\\'.$element->type.'\CallbackAction';
                }
            }
        }

        foreach ($this->app->systemElements as $element) {
            if (!in_array($element->type, $elements)) {
                $elements[] = $element->type;
                if (is_file(Yii::getAlias('@worstinme/zoo/elements/system').DIRECTORY_SEPARATOR.$element->type.DIRECTORY_SEPARATOR.'CallbackAction.php')) {
                    $actions[$element->type] = 'worstinme\zoo\elements\system\\'.$element->type.'\CallbackAction';
                }
            }
        }

        return $actions;

    }
}