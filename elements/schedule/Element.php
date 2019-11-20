<?php

namespace worstinme\zoo\elements\schedule;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Element extends \worstinme\zoo\elements\BaseElement
{

    public $iconClass = 'uk-icon-header';

    
    public $_multiple = true;

    public function getRules()
    {
        return [
            ['autocomplete', 'integer'],
        ];
    }

    public function getLabels()
    {
        return [
            'autocomplete' => Yii::t('backend', 'Автокомплит'),
        ];
    }


    public function getAutocomplete()
    {
        return isset($this->paramsArray['autocomplete'])?$this->paramsArray['autocomplete']:0;
    }

    public function setAutocomplete($a)
    {
        $params = $this->paramsArray;
        $params['autocomplete'] = $a; 
        return $this->paramsArray = $params;
    }


}
