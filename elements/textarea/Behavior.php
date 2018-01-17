<?php

namespace worstinme\zoo\elements\textarea;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'safe'],
		];
	}
}