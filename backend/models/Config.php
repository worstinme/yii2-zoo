<?php

namespace worstinme\zoo\backend\models;

use Yii;

class Config extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%zoo_config}}';
    }

    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['value'], 'string'],
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
