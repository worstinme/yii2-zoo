<?php

namespace worstinme\zoo\elements\textfield_multiple;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			[$attributes,'each', 'rule' => ['string']],
			//[$attributes,'required'],
		];
	}

	public $multiple = true;

	public $value_field = 'value_string';

	public function getValue($attribute) {

    	if (!isset($this->owner->values[$attribute])) {
    		$this->loadAttributesFromElements($attribute);
    	}

        $values = [];

        foreach ($this->owner->values[$attribute] as $value) {
            if (!empty($value[$this->value_field])) {
                $values[] = $value[$this->value_field];
            }
        }

    	return $values;

    }


    public function setValue($attribute,$value) {

        if (!isset($this->owner->values[$attribute])) {
            $this->loadAttributesFromElements($attribute);
        }

        if (is_array($value)) {

            $va = [];

            foreach ($value as $key => $v) {
               

                if ($v !== null && !empty($v)) {

                    $a = [
                        'value_text' =>null,
                        'value_int' =>null,
                        'value_string' =>null,
                        'value_float' =>null,
                    ];

                    $a[$this->value_field] = $v;

                    $va[] = $a;
                }

                
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

        return true;
    }
}