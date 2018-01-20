<?php

namespace worstinme\zoo\elements\checkbox;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

class Element extends \worstinme\zoo\elements\BaseElement
{

    public $iconClass = 'uk-icon-header';


    public function getRules()
    {
        return [
            ['checkboxLabel', 'string','max'=>255],
            ['multiple', 'boolean'],
            ['variant','safe'],
        ];
    }

    public function getLabels()
    {
        return [
            'checkboxLabel' => Yii::t('zoo', 'ELEMENT_CHECKBOX_LABEL'),
            'multiple'=>Yii::t('zoo', 'ELEMENT_CHECKBOX_MULTIPLE'),
            'variant'=>Yii::t('zoo', 'ELEMENT_CHECKBOX_VARIANTS'),
        ];
    }


    public function getCheckboxLabel()
    {
        return isset($this->paramsArray['checkboxLabel'])?$this->paramsArray['checkboxLabel']:null;
    }

    public function setCheckboxLabel($a)
    {
        $params = $this->paramsArray;
        $params['checkboxLabel'] = $a;
        return $this->paramsArray = $params;
    }

    public function getVariant()
    {
        return isset($this->paramsArray['variant'])?$this->paramsArray['variant']:[];
    }

    public function setVariant($a)
    {
        $params = $this->paramsArray;
        $params['variant'] = $a;
        return $this->paramsArray = $params;
    }

    public function getVariants() {
        return ArrayHelper::map($this->variant,'key','value');
    }

    public function getMultiple()
    {
        return isset($this->paramsArray['multiple'])?$this->paramsArray['multiple']:null;
    }

    public function setMultiple($a)
    {
        $params = $this->paramsArray;
        $params['multiple'] = $a;
        return $this->paramsArray = $params;
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/checkbox/_settings';
    }


}