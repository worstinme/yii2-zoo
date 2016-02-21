<?php

namespace worstinme\zoo\fields\textfield;

use Yii;

class ElementBehavior extends \worstinme\zoo\fields\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'string'],
			[$attributes,'required'],
		];
	}
}