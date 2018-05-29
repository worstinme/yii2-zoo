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

    public $field = 'value_int';

    public function getValue() {
        $value = parent::getValue();
        return Yii::$app->formatter->asDate($value == null ? time() : $value,'php:d.m.Y');
    }

    public function setValue($value) {
        $date = \DateTime::createFromFormat('d.m.Y',$value);
        if ($date) {
            return parent::setValue($date->getTimestamp());
        }
        return parent::setValue(null);
    }

}