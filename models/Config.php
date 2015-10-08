<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_config}}".
 *
 * @property integer $id
 * @property string $category
 * @property integer $parent_id
 * @property string $parent_name
 * @property string $value
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['parent_id'], 'integer'],
            [['value'], 'string'],
            [['category', 'parent_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'category' => Yii::t('admin', 'Category'),
            'parent_id' => Yii::t('admin', 'Parent ID'),
            'parent_name' => Yii::t('admin', 'Parent Name'),
            'value' => Yii::t('admin', 'Value'),
        ];
    }
}
