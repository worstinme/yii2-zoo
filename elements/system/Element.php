<?php
/**
 * Created by PhpStorm.
 * User: worstinme
 * Date: 23.07.2017
 * Time: 14:57
 */

namespace worstinme\zoo\elements\system;

use Yii;

class Element extends \yii\base\Component
{
    public $name;
    public $required = false;
    public $app_id;
    public $related = [];
    public $refresh = false;

    public function getAttributeName() {
        return $this->name;
    }

    public function getType() {
        return str_replace("element_","",$this->name);
    }

    public function getLabel() {
        return Yii::t('zoo','LABEL_'.strtoupper($this->name));
    }

    public function getHint() {
        return Yii::t('zoo','HINT_'.strtoupper($this->name));
    }

    public function getIsAvailable() {
        return true;
    }

    public function getApp()
    {
        return Yii::$app->zoo->getApplication($this->app_id);
    }
}