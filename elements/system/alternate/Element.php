<?php

namespace worstinme\zoo\elements\system\alternate;

use Yii;
use worstinme\zoo\backend\models\BackendItems;
use yii\helpers\ArrayHelper;

class Element extends \worstinme\zoo\elements\system\Element
{
    public function getItems($model) {
        return ArrayHelper::map(BackendItems::find()
            ->where([BackendItems::tableName().'.app_id'=>$model->app_id])
            ->andWhere(['not in',BackendItems::tableName().'.lang',[$model->lang]])
            ->all(),'id','name');
    }

    public function getIsAvailable()
    {
        return count(Yii::$app->zoo->languages) > 1 ? true : false;
    }


}
