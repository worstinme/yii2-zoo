<?php

namespace worstinme\zoo\elements\money;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'number'],
		];
	}

	public $field = 'value_float';

}