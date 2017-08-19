<?php

namespace worstinme\zoo\elements\checkbox;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'integer'],
			//[$attributes,'required'],
		];
	}

	public $value_field = 'value_int';
}