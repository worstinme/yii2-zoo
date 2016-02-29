<?php

namespace worstinme\zoo\elements\images;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'string'],
			[$attributes,'required'],
		];
	}

	public $multiple = true;

	public $value_field = 'value_string';
}