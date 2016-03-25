<?php

namespace worstinme\zoo\elements\textfield;

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

	public $value_field = 'value_string';
}