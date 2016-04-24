<?php

namespace worstinme\zoo\elements\datepicker;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'string'],
			//[$attributes,'required'],
		];
	}

	public $value_field = 'value_int';

	public function getValue($attribute) {
		$value = parent::getValue($attribute);
        return Yii::$app->formatter->asDate($value == null ? time() : $value,'php:d.m.Y');
    }

    public function setValue($attribute,$value) {
        return parent::setValue($attribute,Yii::$app->formatter->asTimestamp($value));
    }
}