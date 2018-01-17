<?php

namespace worstinme\zoo\elements\image;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'string'],
			//[$attributes,'required'],
		];
	}

	public $multiple = false;
	public $value_field = 'value_string';

}