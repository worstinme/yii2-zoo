<?php

namespace worstinme\zoo\elements\units;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public function init() {

        parent::init();

    }

    public function getRules()
    {
        return [
            ['related_fields', 'each','rule'=>['integer']],
        ];
    }

    public function getLabels()
    {
        return [
            'related_fields' => Yii::t('backend', 'Связанные поля'),
        ];
    }


    public function getRelated_fields()
    {
        return isset($this->owner->params['related_fields'])?$this->owner->params['related_fields']:[]; 
    }

    public function setRelated_fields($array)
    {
        $params = $this->owner->params;

        if (is_array($array) && count($array)) {
            foreach ($array as $key => $value) {
                if (empty($value)) {
                    unset($array[$key]);
                }
            }
        }        

        $params['related_fields'] = $array; 
        return $this->owner->params = $params;
    }


}