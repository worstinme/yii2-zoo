<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_items_fields}}".
 *
 * @property integer $item_id
 * @property integer $field_id
 * @property integer $variant
 * @property string $value
 */
class ItemFields extends \yii\db\ActiveRecord
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
            [['item_id', 'field_id'], 'required'],
            [['item_id', 'field_id', 'variant'], 'integer'],
            [['value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => Yii::t('admin', 'Item ID'),
            'field_id' => Yii::t('admin', 'Field ID'),
            'variant' => Yii::t('admin', 'Variant'),
            'value' => Yii::t('admin', 'Value'),
        ];
    }

    /**
     * @inheritdoc
     * @return ItemFieldsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemFieldsQuery(get_called_class());
    }
}
