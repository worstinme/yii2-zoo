<?php

namespace worstinme\zoo\models;

use Yii;

class Config extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%config}}';
    }

}
