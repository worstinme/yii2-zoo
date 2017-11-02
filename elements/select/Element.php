<?php

namespace worstinme\zoo\elements\select;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{
    public $value_field = 'value_int';

	public function rules($attributes)
	{
		return [
			[$attributes,'string'],
		];
	}

}