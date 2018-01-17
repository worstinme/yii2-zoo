<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;

class Categories extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%categories}}';
    }

    public function getRelated()
    {
        return $this->hasMany(Categories::className(), ['parent_id' => 'id'])
            ->orderBy('sort ASC');
    }

    public function getItems()
    {
        return $this->hasMany(Items::className(), ['id' => 'item_id'])
            ->viaTable('{{%items_categories}}', ['category_id' => 'id']);
    }

    public function getAlternates() {
        return $this->hasMany(Categories::className(),['id'=>'alternate_id'])
            ->viaTable('{{%categories_alternates}}',['category_id'=>'id']);
    }

    public function getParentCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_id']);
    }

    public function getApp()
    {
        return Yii::$app->zoo->getApplication($this->app_id);
    }

    public function getUrl()
    {
        return ['/'.$this->app_id.'/category','id'=>$this->id,'lang'=>$this->lang];
    }

}
