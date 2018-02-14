<?php

namespace worstinme\zoo\elements\system\alternate;

use Yii;
use worstinme\zoo\backend\models\Items;
use yii\helpers\ArrayHelper;

class Element extends \worstinme\zoo\elements\system\Element
{
    public function getItems($model) {
        return ArrayHelper::map(Items::find()
            ->where([Items::tableName().'.app_id'=>$model->app_id])
            ->andWhere(['not in',Items::tableName().'.lang',[$model->lang]])
            ->all(),'id','name');
    }

    public function getIsAvailable()
    {
        return count(Yii::$app->zoo->languages) > 1 ? true : false;
    }


}