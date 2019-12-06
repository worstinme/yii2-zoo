<?php

namespace worstinme\zoo\elements\system\alternate;

use worstinme\zoo\backend\models\BackendItems;
use Yii;
use worstinme\zoo\elements\BaseCallbackAction;
use yii\helpers\ArrayHelper;

class CallbackAction extends BaseCallbackAction
{
    public function run($app)
    {
        $lang = Yii::$app->request->post('lang');

        $items = BackendItems::find()
            ->where([BackendItems::tableName() . '.app_id' => $app])
            ->andWhere(['not in', BackendItems::tableName() . '.lang', [$lang]])
            ->all();

        return ArrayHelper::toArray($items, [
            'worstinme\zoo\backend\models\BackendItems' => [
                'id',
                'text'=>function($item) {
                    return $item->name.' / '.$item->lang;
                },
            ],
        ]);
    }

}
