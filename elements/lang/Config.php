<?php

namespace worstinme\zoo\elements\lang;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public function getParamsView() {
        return '@worstinme/zoo/elements/lang/params';
    }

}