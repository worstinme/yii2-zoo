<?php

namespace worstinme\zoo\elements\datepicker;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules()
    {
        return [
            [$this->attribute,'string'],
            //[$attributes,'required'],
        ];
    }

    public $value_field = 'value_int';

    public function getValue($attribute) {
        if ($this->owner->hasAttribute($attribute.'_at')) {
            $value = $this->owner->{$attribute.'_at'};
        }
        else {
            $value = parent::getValue($attribute);
        }
        return Yii::$app->formatter->asDate($value == null ? time() : $value,'php:d.m.Y');
    }

    public function setValue($attribute,$value) {

        $value = Yii::$app->formatter->asTimestamp($value);

        if ($this->owner->hasAttribute($attribute.'_at')) {
            return $this->owner->{$attribute.'_at'} = $value;
        }
        else {
            return parent::setValue($attribute,$value);
        }

    }

}