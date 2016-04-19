<?php

namespace worstinme\zoo\elements\image;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public $_multiple = false;

    public function getParamsView() {
        return '@worstinme/zoo/elements/image/_params';
    }
}