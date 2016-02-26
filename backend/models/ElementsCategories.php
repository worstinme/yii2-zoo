<?php

namespace worstinme\zoo\backend\models;

use Yii;


class ElementsCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_elements_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_id', 'category_id'], 'required'],
            [['element_id', 'category_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'element_id' => Yii::t('backend', 'Field ID'),
            'category_id' => Yii::t('backend', 'Category ID'),
        ];
    }
}
