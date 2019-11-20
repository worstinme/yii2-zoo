<?php

namespace worstinme\zoo\elements\video;

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

}
