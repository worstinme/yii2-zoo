<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_fields_categories}}".
 *
 * @property integer $field_id
 * @property integer $category_id
 */
class FieldsCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_fields_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'category_id'], 'required'],
            [['field_id', 'category_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'field_id' => Yii::t('admin', 'Field ID'),
            'category_id' => Yii::t('admin', 'Category ID'),
        ];
    }
}
