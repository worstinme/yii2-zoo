<?php

namespace worstinme\zoo\elements\textfield_multiple;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
	public function rules()
	{
		return [
			[$this->attribute,'each', 'rule' => ['string']],
		];
	}

    public function getMultiple() {
        return true;
    }

	public $value_field = 'value_string';

}
