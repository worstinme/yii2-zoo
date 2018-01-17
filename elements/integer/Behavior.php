<?php

namespace worstinme\zoo\elements\integer;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'integer'],
		];
	}

	public $field = 'value_int';

}