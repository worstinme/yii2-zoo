<?php

namespace worstinme\zoo\elements\select;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    public $field = 'value_int';

	public function rules()
	{
		return [
			[$this->attribute,'string'],
		];
	}

}
