<?php

namespace worstinme\zoo\elements\lang;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'string'],
			//[$attributes,'required'],
		];
	}

}