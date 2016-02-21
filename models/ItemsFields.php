<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_items_fields}}".
 *
 * @property integer $item_id
 * @property string $element
 * @property integer $value_int
 * @property string $value_string
 * @property string $value_text
 * @property double $value_float
 */
class ItemsFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_items_fields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element'], 'required'],
            [['item_id', 'value_int'], 'integer'],
            [['value_text'], 'string'],
            [['value_float'], 'number'],
            [['element', 'value_string'], 'string', 'max' => 255]
        ];
    }

    public static function primaryKey() {
        return ['item_id','element'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => Yii::t('admin', 'Item ID'),
            'element' => Yii::t('admin', 'Element'),
            'value_int' => Yii::t('admin', 'Value Int'),
            'value_string' => Yii::t('admin', 'Value String'),
            'value_text' => Yii::t('admin', 'Value Text'),
            'value_float' => Yii::t('admin', 'Value Float'),
        ];
    }
}
