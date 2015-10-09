<?php

namespace worstinme\zoo\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class Categories extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%zoo_categories}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'app_id', 'created_at', 'updated_at'], 'required'],
            ['alias', 'match', 'pattern' => '#^[\w_-]+$#i'],
            [['type', 'parent_id', 'app_id', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            [['name', 'alias'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'alias' => Yii::t('admin', 'Алиас категории'),
            'name' => Yii::t('admin', 'Название категории'),
            'type' => Yii::t('admin', 'Type'),
            'parent_id' => Yii::t('admin', 'Родительская категория'),
            'app_id' => Yii::t('admin', 'App ID'),
            'sort' => Yii::t('admin', 'Sort'),
            'state' => Yii::t('admin', 'State'),
            'created_at' => Yii::t('admin', 'Created At'),
            'updated_at' => Yii::t('admin', 'Updated At'),
            'params' => Yii::t('admin', 'Params'),
        ];
    }

    public function getRelated()
    {
        return $this->hasMany(Categories::className(), ['parent_id' => 'id'])->orderBy('sort ASC');
    } 

    public function getParent()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_id'])->orderBy('sort ASC');
    } 

    public function getApp()
    {
        return $this->hasOne(Applications::className(), ['id' => 'app_id']);
    } 
}
