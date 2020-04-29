<?php

namespace worstinme\zoo\elements\datepicker;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules()
    {
        return [
            [$this->attribute, 'string'],
        ];
    }

    public $field = 'value_int';

    public function getValue()
    {
        if (!($value = parent::getValue()) && $this->element->defaultCurrentDate) {
            $value = time();
        }
        return $value ? Yii::$app->formatter->asDate($value, 'php:Y-m-d') : null;
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
