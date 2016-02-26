<?php

namespace worstinme\zoo\elements;

use Yii;
use ArrayObject;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

class BaseElementBehavior extends \yii\base\Behavior
{
	public function rules($attributes = null)
	{
		return [];
	}

	public function isRendered($element_name = null) {
		return true;
	}

	public $multiple = false;
}