<?php

namespace worstinme\zoo\elements\price;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'number'],
			[$attributes,'required'],
		];
	}

	public $value_field = 'value_float';
}