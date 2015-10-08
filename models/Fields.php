<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_fields}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property string $type
 * @property integer $required
 * @property integer $filter
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $params
 */
class Fields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_fields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name', 'type', 'created_at', 'updated_at'], 'required'],
            [['required', 'filter', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            [['title', 'name', 'type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'title' => Yii::t('admin', 'Title'),
            'name' => Yii::t('admin', 'Name'),
            'type' => Yii::t('admin', 'Type'),
            'required' => Yii::t('admin', 'Required'),
            'filter' => Yii::t('admin', 'Filter'),
            'state' => Yii::t('admin', 'State'),
            'created_at' => Yii::t('admin', 'Created At'),
            'updated_at' => Yii::t('admin', 'Updated At'),
            'params' => Yii::t('admin', 'Params'),
        ];
    }
}
