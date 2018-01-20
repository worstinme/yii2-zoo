<?php

namespace worstinme\zoo\elements\checkbox;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    public function getMultiple() {
        return $this->element->multiple ? true : false;
    }

    public function rules()
	{
	    if ($this->multiple) {
            return [
                [$this->attribute,'each','rule'=>['in', 'range'=>array_keys($this->element->variants)]],
            ];
        }

        return [
            [$this->attribute,'integer'],
        ];
	}

	public $value_field = 'value_int';
}