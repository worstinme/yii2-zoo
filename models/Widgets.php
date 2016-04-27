<?php

namespace worstinme\zoo\models;

use Yii;


class Widgets extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%zoo_widgets}}';
    }

}
