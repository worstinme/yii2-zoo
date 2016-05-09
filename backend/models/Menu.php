<?php

namespace worstinme\zoo\backend\models;

use Yii;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Categories;

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
            [['type','name','menu'], 'required'],
            [['category_id'],'required','when' => function($model) {return in_array($model->type, [2]); }],
            [['application_id'],'required','when' => function($model) {return in_array($model->type, [1,2,3]); }],
            [['item_id'],'required','when' => function($model) {return in_array($model->type, [3]); }],
            [['url'],'required','when' => function($model) {return in_array($model->type, [4,5]); }],
            [['application_id', 'category_id', 'item_id', 'parent_id', 'sort', 'type'], 'integer'],
            [['url'], 'string'],
            [['name','menu'], 'string', 'max' => 255],
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
            'name' => 'Label',
            'application_id' => 'Application ID',
            'category_id' => 'Category ID',
            'item_id' => 'Item ID',
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
        if (in_array($this->type, [3]) && $this->category_id !== 0 && $this->category_id !== null) {

            return \yii\helpers\ArrayHelper::map((new \yii\db\Query())
                        ->select([ 'a.name','a.id'])
                        ->from(['a'=>'{{%zoo_items}}'])
                        ->innerJoin(['b' => '{{%zoo_items_categories}}','b.item_id = a.id'])
                        ->where(['a.app_id'=>$this->application_id,'b.category_id' => $this->category_id])
                        ->groupBy('a.id')
                        ->all(),'id','name');
        }
        elseif(in_array($this->type, [3])) {
            return \yii\helpers\ArrayHelper::map((new \yii\db\Query())
                        ->select([ 'a.name','a.id'])
                        ->from(['a'=>'{{%zoo_items}}'])
                        ->where(['a.app_id'=>$this->application_id])
                        ->groupBy('a.id')
                        ->all(),'id','name');
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
        elseif($this->type == 3 && $this->item_id > 0) {
            return true;
        }
        elseif($this->type == 4 || $this->type == 5) {
            return true;
        }
        return false;
    }

    public function getRelated() {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id'])->orderBy('sort ASC');
    }

    public function getParents() {
        if ($this->isNewRecord) {
            return self::find()->select(['name'])->where(['menu'=>$this->menu])->indexBy('id')->column();
        }
        else {
            return self::find()->select(['name'])->where(['<>','id',$this->id])->andWhere(['menu'=>$this->menu])->indexBy('id')->column();
        }
    }

    public function getActive() {
        switch ($this->type) {

            case 3:
                null;
            break;
            
            default: null;
                break;
        }
    }

    public function getUrl() {

        if ($this->type == 4) {
            return \yii\helpers\Json::decode($this->url);
        }
        elseif ($this->type == 5) {
            return $this->url;
        }
        elseif ($this->type == 1) {
            if (($application = Applications::findOne($this->application_id)) !== null) {
                return $application->url;
            }
        }
        elseif ($this->type == 2) {
            if (($category = Categories::findOne($this->category_id)) !== null) {
                return $category->url;
            }
        }
        elseif ($this->type == 3) {

            $item = Items::find()->where([Items::tablename().'.id'=>$this->item_id])->one();

            if ($item !== null) {
                return $item->url;
            } 
        }

        return '#';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->updated_at = time();
            return true;
        } else {
            return false;
        }
    }
}
