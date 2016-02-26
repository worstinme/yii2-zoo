<?php

namespace worstinme\zoo\backend\models;

use Yii;

class Menu extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','label','menu'], 'required'],
            [['category_id'],'required','when' => function($model) {return in_array($model->type, [2,3]); }],
            [['application_id'],'required','when' => function($model) {return in_array($model->type, [1,2,3]); }],
            [['item_id'],'required','when' => function($model) {return in_array($model->type, [3]); }],
            [['url'],'required','when' => function($model) {return in_array($model->type, [4,5]); }],
            [['application_id', 'category_id', 'item_id', 'parent_id', 'sort', 'type'], 'integer'],
            [['url'], 'string'],
            [['label', 'class','menu'], 'string', 'max' => 255],
            ['menu', 'match', 'pattern' => '#^[\w_-]+$#i'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'application_id' => 'Application ID',
            'category_id' => 'Category ID',
            'item_id' => 'Item ID',
            'class' => 'Class',
            'parent_id' => 'Parent ID',
            'sort' => 'Sort',
            'type' => 'Type',
            'url' => 'Url',
        ];
    }

    public function getTypes() {
        return [
            1=>Yii::t('backend','Приложение'),
            2=>Yii::t('backend','Категория'),
            3=>Yii::t('backend','Материал'),
            4=>Yii::t('backend','JSON параметры'),
            5=>Yii::t('backend','Произвольная ссылка'),
        ];
    }

    public function getApplications() {
        if (in_array($this->type, [1,2,3])){
            return Applications::find()->select(['title'])->indexBy('id')->column();
        }
        return null;
    }

    public function getCategories() {
        if (in_array($this->type, [2,3]) && $this->application_id !== null && ($application = Applications::findOne($this->application_id)) !== null) {
            return $application->catlist;
        }
        return null;
    }

    public function getItems() {
        if (in_array($this->type, [3]) && $this->category_id !== 0) {
            $items = Items::find()->joinWith('categories')->where(['{{%zoo_categories}}.id'=>$this->category_id])->asArray()->all();
            return \yii\helpers\ArrayHelper::map($items,'id','name');
        }
        return null;
    }

    public function getCheck() {
        if ($this->type == 1 && $this->application_id !== null && Applications::findOne($this->application_id) !== null) {
            return true;
        }
        elseif($this->type == 2 && $this->category_id !== null && Categories::findOne($this->category_id) !== null) {
            return true;
        }
        elseif($this->type == 3 && $this->category_id !== null && Items::findOne($this->item_id) !== null) {
            return true;
        }
        elseif($this->type == 4 || $this->type == 5) {
            return true;
        }
        return false;
    }

    public function getParents() {
        if ($this->isNewRecord) {
            return self::find()->select(['label'])->where(['menu'=>$this->menu])->indexBy('id')->column();
        }
        else {
            return self::find()->select(['label'])->where(['<>','id',$this->id])->andWhere(['menu'=>$this->menu])->indexBy('id')->column();
        }
    }
}
