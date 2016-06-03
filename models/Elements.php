<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class Elements extends \yii\db\ActiveRecord
{

    public $value;

    public function getMultiple() {
        return isset($this->_multiple) ? $this->_multiple : false;
    }

    public static function tableName()
    {
        return '{{%zoo_elements}}';
    }

    public function afterFind()
    {
        $this->params = !empty($this->params) ? Json::decode($this->params) : [];

        if ($this->type !== null  && is_file(Yii::getAlias('@worstinme/zoo').'/elements/'.$this->type.'/Config.php')) {
            $element = '\worstinme\zoo\elements\\'.$this->type.'\Config';
            $this->attachBehaviors([
                $element::className()          // an anonymous behavior
            ]);
        }

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

    public function getFormView() {
        return '@worstinme/zoo/elements/'.$this->type.'/form';
    }
    
    public function getAdminHint() {
        return isset($this->params['adminHint'])?$this->params['adminHint']:null; 
    }

    //related
    public function getRelated() { 
        return !empty($this->params['related'])?$this->params['related']:null; 
    }

    //required
    public function getRequired() { 
        return isset($this->params['required'])?$this->params['required']:null; 
    }

    //refresh
    public function getRefresh() { 
        return isset($this->params['refresh'])?$this->params['refresh']:null; 
    }

    //sorter
    public function getSorter() { 
        return isset($this->params['sorter'])?$this->params['sorter']:null; 
    }

    //filter
    public function getFilter() { 
        return isset($this->params['filter'])?$this->params['filter']:null; 
    }
    //filter
    public function getAdminFilter() { 
        return isset($this->params['adminFilter'])?$this->params['adminFilter']:null; 
    }
    //filter
    public function getSearch() { 
        return isset($this->params['search'])?$this->params['search']:null; 
    }

    //allcategories
    public function getAllcategories()
    {
        return isset($this->params['allcategories'])?$this->params['allcategories']:1; 
    }

}