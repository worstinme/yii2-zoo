<?php

namespace worstinme\zoo\elements\buy;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'string'],
			[$attributes,'required'],
		];
	}

	public function getValue($attribute) {
    	return true;
    }
}