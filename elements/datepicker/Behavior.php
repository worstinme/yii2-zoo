<?php

namespace worstinme\zoo\elements\datepicker;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules()
    {
        return [
            [$this->attribute, 'string'],
            //[$attributes,'required'],
        ];
    }

    public $field = 'value_int';

    public function getValue()
    {
        $value = parent::getValue();
        return Yii::$app->formatter->asDate($value ?? time(), 'php:Y-m-d');
    }

    public function setValue($value)
    {
        return parent::setValue(Yii::$app->formatter->asTimestamp($value));
    }

    protected function saveElement($values = null)
    {
        parent::saveElement(parent::getValue());
    }

}
