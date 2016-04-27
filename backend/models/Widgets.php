<?php

namespace worstinme\zoo\backend\models;

use Yii;


class Widgets extends \worstinme\zoo\models\Widgets
{

    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['params'], 'string'],
            [['type', 'name', 'position', 'bound'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'position' => 'Position',
            'bound' => 'Bound',
            'params' => 'Params',
        ];
    }
}
