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

    public function getFormView() {
        return '@worstinme/zoo/elements/'.$this->type.'/_form';
    }

    //related
    public function getRelated() { 
        return !empty($this->params['related'])?$this->params['related']:null; 
    }

    //required
    public function getRequired() { 
        return !empty($this->params['required'])?$this->params['required']:null; 
    }

    //refresh
    public function getRefresh() { 
        return !empty($this->params['refresh'])?$this->params['refresh']:null; 
    }

    //filter
    public function getFilter() { 
        return !empty($this->params['filter'])?$this->params['filter']:null; 
    }

    //allcategories
    public function getAllcategories()
    {
        return !empty($this->params['allcategories'])?$this->params['allcategories']:1; 
    }

}