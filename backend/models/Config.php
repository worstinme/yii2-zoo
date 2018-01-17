<?php

namespace worstinme\zoo\backend\models;

use Yii;

class Config extends \worstinme\zoo\models\Config
{
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['value','category'], 'string'],
            ['name', 'trim'],
            [['name','comment'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование параметра',
            'value' => 'Значение',
            'comment'=> 'Комментарий',
        ];
    }
}
