<?php

namespace worstinme\zoo\elements\category;

use Yii;
use yii\helpers\ArrayHelper;

class Config extends \yii\base\Behavior
{

	public function getParamsView() {
        return '@worstinme/zoo/elements/category/params';
    }
}