<?php

namespace worstinme\zoo\elements\system\category;

use worstinme\zoo\backend\models\Items;
use Yii;
use worstinme\zoo\elements\BaseCallbackAction;
use yii\helpers\ArrayHelper;

class CallbackAction extends BaseCallbackAction
{
    public function run($app)
    {
        $lang = Yii::$app->request->post('lang');

        $items = \worstinme\zoo\backend\models\Categories::buildTree($lang);

        foreach ($items as $key=>&$item) {
            $item = [
                'id'=>$key,
                'text'=>$item,
            ];
        }

        return array_values($items);
    }

}