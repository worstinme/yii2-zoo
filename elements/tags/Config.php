<?php

namespace worstinme\zoo\elements\tags;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public function init() {

        parent::init();

    }

    
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
        return isset($this->owner->params['autocomplete'])?$this->owner->params['autocomplete']:0; 
    }

    public function setAutocomplete($a)
    {
        $params = $this->owner->params;
        $params['autocomplete'] = $a; 
        return $this->owner->params = $params;
    }

    public function getTags() {

        return ArrayHelper::map((new Query())
            ->select(['value_string','count(item_id) as c'])
            ->from('{{%zoo_items_elements}}')
            ->groupBy('value_string')
            ->orderBy('c DESC')
            ->where(['element'=>$this->owner->name])
            ->all(),'value_string',function($e){
                                        return $e['value_string'].' ('.$e['c'].')';
        });
    }
}