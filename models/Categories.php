<?php

namespace worstinme\zoo\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

class Categories extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%zoo_categories}}';
    }

    public function afterFind()
    {
        $this->params = !empty($this->params) ? Json::decode($this->params) : [];
        return parent::afterFind();
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->params = Json::encode($this->params);
            
            return true;
        }
        else return false;
    }

    public function afterSave($insert, $changedAttributes)
    {   
        $this->params = Json::decode($this->params);
        return parent::afterSave($insert, $changedAttributes);
    }

    //metaTitle
    public function getMetaTitle() {
        return !empty($this->params['metaTitle']) ? $this->params['metaTitle'] : $this->name;
    }

    //metaTitle
    public function getImage() {
        return !empty($this->params['image']) ? $this->params['image'] : null;
    }

    //metaKeywords
    public function getMetaKeywords() {
        return !empty($this->params['metaKeywords']) ? $this->params['metaKeywords'] : null;
    }

    //metaDescription
    public function getMetaDescription() {
        return !empty($this->params['metaDescription']) ? $this->params['metaDescription'] : null;
    }

    //metaDescription
    public function getContent() {
        return !empty($this->params['content']) ? $this->params['content'] : null;
    }

    //metaDescription
    public function getIntro() {
        return !empty($this->params['intro']) ? $this->params['intro'] : null;
    }

    public function getTemplate($name) {
        return !empty($this->params['templates']) && !empty($this->params['templates']['name']) ? $this->params['templates']['name'] : null;
    }

    public function getTemplates() {
        return !empty($this->params['templates']) ? $this->params['templates'] : null;
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

    public function getUrl() {

        if ($this->parent_id != null && $this->parent !== null) {
            if ($this->parent->parent_id != null && $this->parent->parent !== null) {
                return ['/'.$this->app->name.'/abc','a'=>$this->parent->parent->alias,'b'=>$this->parent->alias,'c'=>$this->alias];
            }
            else {
                return ['/'.$this->app->name.'/ab','a'=>$this->parent->alias,'b'=>$this->alias];
            }
        }
        else {
            return ['/'.$this->app->name.'/a','a'=>$this->alias];
        }

    }

}
