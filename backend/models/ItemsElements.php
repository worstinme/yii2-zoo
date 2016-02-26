<?php

namespace worstinme\zoo\backend\models;

use Yii;

class ItemsElements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_items_elements}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'element'], 'required'],
            [['item_id', 'value_int'], 'integer'],
            [['value_text'], 'string'],
            [['value_float'], 'number'],
            [['value_string', 'element'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'element' => 'Element ID',
            'value_text' => 'Value Text',
            'value_int' => 'Value Int',
            'value_string' => 'Value String',
            'value_float' => 'Value Float',
        ];
    }
}
