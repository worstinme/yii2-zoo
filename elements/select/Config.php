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
            ['variants', 'each', 'rule'=>['string','max'=>255]],
        ];
    }

    public function getLabels()
    {
        return [
            'variants' => Yii::t('backend', 'Варианты значений для выбора'),
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/select/_settings';
    }


    public function getVariants()
    {
        return !empty($this->owner->params['variants'])?$this->owner->params['variants']:[]; 
    }

    public function setVariants($a)
    {
        $params = $this->owner->params;

        if (is_array($a) && count($a)) {
	        foreach ($a as $key => $value) {
	        	if (empty($value)) {
	        		unset($a[$key]);
	        	}
	        }
        }

        $params['variants'] = $a; 

        return $this->owner->params = $params;
    }


}