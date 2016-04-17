<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_items_elements}}".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $element_id
 * @property string $value_text
 * @property integer $value_int
 * @property string $value_string
 * @property double $value_float
 */
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
            [['value_string','element'], 'string', 'max' => 255]
        ];
    }

    public function getItems() {
        return $this->hasOne(ItemsElements::className(),['item_id'=>'id']);
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
