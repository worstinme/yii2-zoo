<?php

namespace worstinme\zoo\elements\system\name;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'string', 'max'=>255],
			[$this->attribute,'required'],
		];
	}

}