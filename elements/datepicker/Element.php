<?php

namespace worstinme\zoo\elements\datepicker;


class Element extends \worstinme\zoo\elements\BaseElement
{
    public $defaultStore = 'value_int';
    public $itemsStore = 'integer';


    public function getRules()
    {
        return [
            ['defaultCurrentDate', 'boolean'],
        ];
    }

    public function getDefaultCurrentDate()
    {
        return $this->paramsArray['defaultCurrentDate'] ?? false;
    }

    public function setDefaultCurrentDate($value)
    {
        $params = $this->paramsArray;
        $params['defaultCurrentDate'] = $value;
        return $this->paramsArray = $params;
    }

    public function getConfigView()
    {
        return '@worstinme/zoo/elements/datepicker/_settings';
    }
}
