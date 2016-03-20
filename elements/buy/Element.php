<?php

namespace worstinme\zoo\elements\buy;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [ ];
	}

	public function getValue($attribute) {
    	return true;
    }
}