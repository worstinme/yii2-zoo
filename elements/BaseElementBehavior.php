<?php

namespace worstinme\zoo\elements;

use Yii;
use ArrayObject;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

class BaseElementBehavior extends \yii\base\Behavior
{

	public $value_field = 'value_text';

	public function rules($attributes)
	{
		return [];
	}

	public function isRendered($element_name = null) {
		return true;
	}

	public $multiple = false;

	public function LoadAttributesFromElements($attribute) {
		$value = [];

    	foreach ($this->owner->itemsElements as $element) {
    		if ($element->element == $attribute) {
    			$value[] = [
					'value_text' =>$element->value_text,
					'value_int' =>$element->value_int,
					'value_string' =>$element->value_string,
					'value_float' =>$element->value_float,
				];
    		}
    	}

    	return $this->owner->values[$attribute] = $this->multiple ? $value : (isset($value[0]) ? $value[0] : [
					'value_text' =>null,
					'value_int' =>null,
					'value_string' =>null,
					'value_float' =>null,
				]);
	}

    public function getValue($attribute) {

    	if (!isset($this->owner->values[$attribute])) {
    		$this->loadAttributesFromElements($attribute);
    	}

    	if ($this->multiple === false) {
    		return isset($this->owner->values[$attribute][$this->value_field]) ? $this->owner->values[$attribute][$this->value_field] : null;
    	}
    	else {
    		return yii\helpers\ArrayHelper::getColumn($this->owner->values[$attribute],$this->value_field);
    	}

    }

    public function setValue($attribute,$value) {

    	if (!isset($this->owner->values[$attribute])) {
    		$this->loadAttributesFromElements($attribute);
    	}

    	if ($this->multiple === false) {
    		$this->owner->values[$attribute][$this->value_field] = $value;
    	}
    	else {
    		if (is_array($value)) {

    			$va = [];

    			foreach ($value as $key => $v) {
    				$a = [
    					'value_text' =>null,
						'value_int' =>null,
						'value_string' =>null,
						'value_float' =>null,
    				];

    				$a[$this->value_field] = $v;

    				$va[] = $a;
    			}

    			$this->owner->values[$attribute] = $va;

    		}
    		else {

    			$a = [
    					'value_text' =>null,
						'value_int' =>null,
						'value_string' =>null,
						'value_float' =>null,
    				];

    			$a[$this->value_field] = $value;
    				 
    			$this->owner->values[$attribute] = [$a];
    		}
    	}

        return true;
    }
}