<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_items}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $flag
 * @property integer $sort
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $params
 */
class Items extends \yii\db\ActiveRecord
{
    private $fields;

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
            [['user_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'flag', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string']
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

    public function getFields() {
        if(!count($this->fields)) {
            $this->fields = Fields::find()
                    ->leftJoin('{{@zoo_fields_categories}}','{{@zoo_fields_categories}}.field_id = id')
                    ->where(['{{@zoo_fields_categories}}.category_id'=>array_push($this->categories,0)])
                    ->indexBy("id")
                    ->all();
        }        
        return $this->fields;
    }

    public function getElements() {
        return $this->hasMany(ItemFields::className(), ['item_id' => 'id']);
    }
}
