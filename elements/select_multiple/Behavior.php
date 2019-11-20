<?php

namespace worstinme\zoo\elements\select_multiple;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules()
    {
        return [
            [$this->attribute, 'safe'],
        ];
    }

    public function getMultiple() {
        return true;
    }

    public $value_field = 'value_int';
    
}
