<?php

namespace worstinme\zoo\elements\related;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules()
	{
		return [
			[$this->attribute,'integer'],
		];
	}

    public $value_field = 'value_int';

}