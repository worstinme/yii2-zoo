<?php

namespace worstinme\zoo\elements\tags;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Element extends \worstinme\zoo\elements\BaseElement
{

    public $iconClass = 'uk-icon-image';
    public $_multiple = true;

    public function getTags() {

        return ArrayHelper::map((new Query())
            ->select(['value_string','count(item_id) as c'])
            ->from('{{%items_elements}}')
            ->groupBy('value_string')
            ->orderBy('c DESC')
            ->where(['element'=>$this->name])
            ->all(),'value_string',function($e){
                                        return $e['value_string'].' ('.$e['c'].')';
        });
    }
}