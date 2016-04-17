<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

class Applications extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%zoo_applications}}';
    }


    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        return parent::afterFind();
    }

    public function getParentCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id'=>0])->orderBy('sort ASC');
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->orderBy('sort ASC');
    }


    public function getTemplate($name = null) {
        $params = !empty($this->templates) ? \yii\helpers\Json::decode($this->templates) : []; 
        if ($name === null) {
            return $params;
        }
        return isset($params[$name]) ? $this->params[$name] : [];
    }

    //frontpage
    public function getFrontpage() { 
        return isset($this->params['frontpage'])?$this->params['frontpage']:''; 
    }

    public function setFrontpage($preview) { 
        $params = $this->params;
        $params['frontpage'] = $preview; 
        return $this->params = $params;
    }

    //categorieslinks
    public function getCatlinks() { 
        return isset($this->params['catlinks'])?$this->params['catlinks']:''; 
    }

    public function setCatlinks($preview) { 
        $params = $this->params;
        $params['catlinks'] = $preview; 
        return $this->params = $params;
    }

    //metaTitle
    public function getMetaTitle() {
        $params = $this->params;
        return isset($params['metaTitle']) ? $params['metaTitle'] : '';
    }
    public function setMetaTitle($s) {
        $params = $this->params; $params['metaTitle'] = $s;
        return $this->params = $params;
    }

    //metaKeywords
    public function getMetaKeywords() {
        $params = $this->params;
        return isset($params['metaKeywords']) ? $params['metaKeywords'] : '';
    }
    public function setMetaKeywords($s) {
        $params = $this->params; $params['metaKeywords'] = $s;
        return $this->params = $params;
    }

    //metaDescription
    public function getMetaDescription() {
        $params = $this->params;
        return isset($params['metaDescription']) ? $params['metaDescription'] : '';
    }
    public function setMetaDescription($s) {
        $params = $this->params; $params['metaDescription'] = $s;
        return $this->params = $params;
    }

    //view Path
    public function getViewPath() { 
        return isset($this->params['viewPath'])?$this->params['viewPath']:''; 
    }

    public function setViewPath($preview) { 
        $params = $this->params;
        $params['viewPath'] = $preview; 
        return $this->params = $params;
    }

    public function getElements() {
        return $this->hasMany(Elements::className(), ['app_id' => 'id'])->indexBy('name');
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

    public function getCatlist() {
        $parents = Categories::find()->where(['app_id'=>$this->id,'parent_id'=>0])->orderBy('sort ASC')->all();
        return $catlist = count($parents) ? $this->getRelatedList($parents,[],'') : [];
    }
    protected function getRelatedList($items,$array,$level) {
        if (count($items)) {
            foreach ($items as $item) {
                $array[$item->id] = $level.' '.$item->name;
                if (count($item->related)) {
                    $array = $this->getRelatedList($item->related,$array,$level.'—');
                }
            }
        }
        return $array;
    }

}