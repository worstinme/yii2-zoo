<?php

namespace worstinme\zoo\frontend\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_elements}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property string $type
 * @property integer $required
 * @property integer $filter
 * @property integer $state
 * @property integer $app_id
 * @property string $params
 */
class Elements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_elements}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name', 'type', 'app_id'], 'required'],
            [['required', 'filter', 'state', 'app_id'], 'integer'],
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
            'id' => 'ID',
            'title' => 'Title',
            'name' => 'Name',
            'type' => 'Type',
            'required' => 'Required',
            'filter' => 'Filter',
            'state' => 'State',
            'app_id' => 'App ID',
            'params' => 'Params',
        ];
    }

    
}
