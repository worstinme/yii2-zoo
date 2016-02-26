<?php

namespace worstinme\zoo\elements\textarea;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'safe'],
			[$attributes,'required'],
		];
	}
}