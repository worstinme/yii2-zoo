<?php

namespace worstinme\zoo\elements\related;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    public function getMultiple() {
        return true;
    }

    public function rules()
    {
        return [
            [$this->attribute, 'safe'],
        ];
    }

    public $value_field = 'value_int';

}