<?php

namespace worstinme\zoo\elements\video;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
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