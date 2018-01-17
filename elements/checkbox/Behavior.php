<?php

namespace worstinme\zoo\elements\checkbox;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'integer'],
			//[$attributes,'required'],
		];
	}

	public $value_field = 'value_int';
}