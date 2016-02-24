<?php

namespace worstinme\zoo\models;

use Yii;

class Items extends \yii\db\ActiveRecord
{
    private $fields;
    private $categories;
    private $expectedFields;

    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    {
        return [
            [['user_id', 'created_at', 'updated_at','categories'], 'required'],
            [['user_id', 'flag', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            ['categories','each','rule'=>['integer'],'message'=>'Выберите категорию'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'user_id' => Yii::t('admin', 'User ID'),
            'flag' => Yii::t('admin', 'Flag'),
            'sort' => Yii::t('admin', 'Sort'),
            'state' => Yii::t('admin', 'State'),
            'created_at' => Yii::t('admin', 'Created At'),
            'updated_at' => Yii::t('admin', 'Updated At'),
            'params' => Yii::t('admin', 'Params'),
        ];
    }

    /**
     * @inheritdoc
     * @return ItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemsQuery(get_called_class());
    }



    public function getElements() {
        return $this->hasMany(ItemsFields::className(), ['item_id' => 'id']);
    }

}
