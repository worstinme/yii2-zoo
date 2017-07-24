<?php
/**
 * Created by PhpStorm.
 * User: worstinme
 * Date: 23.07.2017
 * Time: 12:53
 */

namespace worstinme\zoo\elements;

use worstinme\zoo\backend\models\Elements;
use Yii;
use worstinme\zoo\models\Items;
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

        if (($this->element = Elements::findOne(['name'=>$params['element']])) === null) {
            throw new InvalidConfigException(get_class($this) . ' element '.$params['element'].' not found');
        }

        return parent::runWithParams($params);

    }

    public function findModel($model_id) {
        return Items::findOne($model_id);
    }

}