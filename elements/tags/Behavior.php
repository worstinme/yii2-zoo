<?php

namespace worstinme\zoo\elements\tags;

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
}