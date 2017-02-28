<?php

namespace worstinme\zoo\elements\alternate;

use worstinme\zoo\models\Items;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-align-left';

    public $_multiple = true;

    public function getRules()
    {
        return [
        ];
    }

    public function getLabels()
    {
        return [
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/alternate/_settings';
    }

    public function getItems() {
        return ArrayHelper::map(Items::find()
            ->where([Items::tableName().'.app_id'=>$this->owner->app_id])
            //->andWhere(['is not',Items::tableName().'.lang',$this->owner->owner->lang])
            ->all(),'id','name');
    }

}