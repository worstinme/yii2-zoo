<?php

namespace worstinme\zoo\elements\alias;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-align-left';


    public function getRules()
    {
        return [
            ['relatedField', 'safe'],
            ['relatedField', 'required'],
        ];
    }

    public function getLabels()
    {
        return [
            'relatedField' => Yii::t('backend', 'Связанное поле'),
        ];
    }


    public function getRelatedField()
    {
        return isset($this->owner->params['relatedField'])?$this->owner->params['relatedField']:0; 
    }

    public function setRelatedField($a)
    {
        $params = $this->owner->params;
        $params['relatedField'] = $a; 
        return $this->owner->params = $params;
    }


}