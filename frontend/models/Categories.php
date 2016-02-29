<?php

namespace worstinme\zoo\frontend\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_categories}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $parent_id
 * @property integer $app_id
 * @property integer $sort
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $params
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'app_id', 'created_at', 'updated_at'], 'required'],
            [['parent_id', 'app_id', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            [['name', 'alias'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alias' => 'Alias',
            'parent_id' => 'Parent ID',
            'app_id' => 'App ID',
            'sort' => 'Sort',
            'state' => 'State',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'params' => 'Params',
        ];
    }
}
