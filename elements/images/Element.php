<?php

namespace worstinme\zoo\elements\images;

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

	public function LoadAttributesFromElements($attribute) {
		$value = [];

    	foreach ($this->owner->itemsElements as $element) {
    		if ($element->element == $attribute) {

    			if ($element->value_int !== null && $element->value_text !== null) {

                    $img_content = file_get_contents($element->value_text);

                    if ($img_content) {

                        $filenameparts = explode(".",$element->value_text );
                        $fileext = array_pop($filenameparts);

                        $filename = md5($img_content).'.'.$fileext; 

                        $dir = '/images/uploads/'.$filename[0].$filename[1].'/'.$filename[2].$filename[3].'/';

                        if (!is_dir(Yii::getAlias('@webroot').$dir)) {
                            @mkdir(Yii::getAlias('@webroot').$dir, 0700, true);
                        }

                        $upload = file_put_contents(Yii::getAlias('@webroot').$dir.$filename, $img_content);
                        if ($upload) {
                            $element->value_string = $dir.$filename;
                            $element->value_int = null;
                            $element->save();
                        } 
                    }
                }

    			$value[] = [
                    'id'=>$element->id,
					'value_text' =>$element->value_text,
					'value_int' =>$element->value_int,
					'value_string' =>$element->value_string,
					'value_float' =>$element->value_float,
				];
    		}
    	}

        //print_r($value);

    	return $this->owner->values[$attribute] = $value;
	}

    public function getValue($attribute) {

    	if (!isset($this->owner->values[$attribute])) {
    		$this->loadAttributesFromElements($attribute);
    	}

    	return yii\helpers\ArrayHelper::getColumn($this->owner->values[$attribute],$this->value_field);

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