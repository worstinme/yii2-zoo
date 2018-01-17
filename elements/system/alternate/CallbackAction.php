<?php

namespace worstinme\zoo\elements\system\alternate;

use worstinme\zoo\backend\models\Items;
use Yii;
use worstinme\zoo\elements\BaseCallbackAction;
use yii\helpers\ArrayHelper;

class CallbackAction extends BaseCallbackAction
{
    public function run($app)
    {
        $lang = Yii::$app->request->post('lang');

        $items = Items::find()
            ->where([Items::tableName() . '.app_id' => $app])
            ->andWhere(['not in', Items::tableName() . '.lang', [$lang]])
            ->all();

        return ArrayHelper::toArray($items, [
            'worstinme\zoo\backend\models\Items' => [
                'id',
                'text'=>function($item) {
                    return $item->name.' / '.$item->lang;
                },
            ],
        ]);
    }

}