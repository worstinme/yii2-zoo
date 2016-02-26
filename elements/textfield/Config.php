<?php

namespace worstinme\zoo\elements\textfield;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Element extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public function init() {

        parent::init();

    }

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
        return isset($this->owner->params['autocomplete'])?$this->owner->params['autocomplete']:0; 
    }

    public function setAutocomplete($a)
    {
        $params = $this->owner->params;
        $params['autocomplete'] = $a; 
        return $this->owner->params = $params;
    }


}