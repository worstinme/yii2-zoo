<?php

namespace worstinme\zoo\elements\textfield_int;

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