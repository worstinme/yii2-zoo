<?php

namespace worstinme\zoo\elements\string;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'string','max'=>255],
		];
	}

	public $field = 'value_string';

}