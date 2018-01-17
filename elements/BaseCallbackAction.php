<?php
/**
 * Created by PhpStorm.
 * User: worstinme
 * Date: 23.07.2017
 * Time: 12:53
 */

namespace worstinme\zoo\elements;

use Yii;
use worstinme\zoo\backend\models\Items;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class BaseCallbackAction extends Action
{
    public $element;

    public function init() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function runWithParams($params)
    {
        if (empty($params['element'])) {
            throw new InvalidConfigException(get_class($this) . ' must send Element Name.');
        }

        $this->element = $this->controller->app->getElement($params['element']);

        return parent::runWithParams($params);

    }

    public function findModel($model_id) {
        return Items::findOne($model_id);
    }

}