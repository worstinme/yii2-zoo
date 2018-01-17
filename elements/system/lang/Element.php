<?php

namespace worstinme\zoo\elements\system\lang;

use Yii;

class Element extends \worstinme\zoo\elements\system\Element
{
    public function getIsAvailable()
    {
        return count(Yii::$app->zoo->languages) ? true : false;
    }
}