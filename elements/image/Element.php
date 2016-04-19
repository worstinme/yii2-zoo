<?php

namespace worstinme\zoo\elements\image;

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

	public $multiple = false;
	public $value_field = 'value_string';

}