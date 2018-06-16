<?php

namespace worstinme\zoo\elements\alias;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Element extends \worstinme\zoo\elements\system\Element
{
    public function getIsAvailable()
    {
        return true;
    }
}