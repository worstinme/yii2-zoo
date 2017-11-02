<?php

namespace worstinme\zoo\elements\select;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-align-left';


    public function getRules()
    {
        return [
            ['variant', 'each', 'rule'=>['string','max'=>255]],
            ['variantsParams','string','max'=>255],
            ['variantsType', 'integer'],
        ];
    }

    public function getLabels()
    {
        return [
            'variant' => Yii::t('backend', 'Варианты значений для выбора из списка'),
            'variantsType' => Yii::t('backend', 'Типы вариантов'),
            'variantsParams' => Yii::t('backend', 'Ключ массива значений из Yii::$app->params[key]'),
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/select/_settings';
    }

    public function getVariantsType(){
        return !empty($this->owner->paramsArray['variantsType'])?$this->owner->paramsArray['variantsType']:null;
    }

    public function setVariantsType($s){
        $params = $this->owner->paramsArray;
        $params['variantsType'] = $s;
        return $this->owner->paramsArray = $params;
    }

    public function getVariantsTypes() {
        return [
            'Yii::$app->params',
            'Введенные варианты',
            '-- Массив из настроек ZOO --',
        ];
    }

    public function getVariantsParams(){
        return !empty($this->owner->paramsArray['variantsParams'])?$this->owner->paramsArray['variantsParams']:null;
    }

    public function setVariantsParams($s){
        $params = $this->owner->paramsArray;
        $params['variantsParams'] = $s;
        return $this->owner->paramsArray = $params;
    }

    public function getVariants() {
        if ($this->variantsType == 0 && !empty($this->variantsParams) && !empty(Yii::$app->params[$this->variantsParams]) && is_array(Yii::$app->params[$this->variantsParams])) {
            return Yii::$app->params[$this->variantsParams];
        }
        elseif(is_array($this->variant)) {
            return $this->variant;
        }
        else {
            return [];
        }
    }

    public function getVariant()
    {
        return !empty($this->owner->paramsArray['variant'])?$this->owner->paramsArray['variant']:[];
    }

    public function setVariant($a)
    {
        $params = $this->owner->paramsArray;

        if (is_array($a) && count($a)) {
	        foreach ($a as $key => $value) {
	        	if (empty($value)) {
	        		unset($a[$key]);
	        	}
	        }
        }

        $params['variant'] = $a; 

        return $this->owner->paramsArray = $params;
    }


}