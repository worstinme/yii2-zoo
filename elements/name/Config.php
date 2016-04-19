<?php

namespace worstinme\zoo\elements\name;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public function init() {

        parent::init();

    }

    public function getParamsView() {
        return '@worstinme/zoo/elements/name/_params';
    }


}