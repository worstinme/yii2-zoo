<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;

class Applications extends \yii\db\ActiveRecord
{
    private $catlist;

    private $test;

    public static function tableName()
    {
        return '{{%zoo_applications}}';
    }

    public function test() {
        if ($this->test === null) {
            $this->test = rand();
        }
        return $this->test;
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

    public function getParentCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id'=>0,'state'=>1])->orderBy('sort ASC');
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['state'=>1])->orderBy('sort ASC');
    }

    public function getItems() {
        return $this->hasMany(Items::className(), ['app_id' => 'id'])->inverseOf('app');
    }


    public function getTemplate($name = null) {
        $params = !empty($this->templates) ? Json::decode($this->templates) : []; 
        if ($name === null) {
            return $params;
        }
        return isset($params[$name]) ? $params[$name] : [];
    }

    //metaTitle
    public function getMetaTitle() {
        return !empty($this->params['metaTitle']) ? $this->params['metaTitle'] : $this->title;
    }

    //metaTitle
    public function getFilters() {
        return !empty($this->params['filters']) ? $this->params['filters'] : null;
    }

    public function getItemsSearch() {
        return !empty($this->params['itemsSearch']) ? $this->params['itemssearch'] : null;
    }

    public function getItemsSort() {
        return !empty($this->params['itemsSort']) ? $this->params['itemsSort'] : null;
    }

    public function getDefaultPageSize() {
        return !empty($this->params['defaultPageSize']) ? $this->params['defaultPageSize'] : 24;
    }

    public function getItemsColumns() {
        return !empty($this->params['itemsColumns']) ? $this->params['itemsColumns'] : 1;
    }

    public function getPjax() {
        return !empty($this->params['pjax']) ? $this->params['pjax'] : null;
    }

    //metaKeywords
    public function getMetaKeywords() {
        return !empty($his->params['metaKeywords']) ? $this->params['metaKeywords'] : null;
    }


    //metaDescription
    public function getMetaDescription() {
        return !empty($this->params['metaDescription']) ? $this->params['metaDescription'] : null;
    }

    public function getSimpleItemLinks() {
        return !empty($this->params['simpleItemLinks']) ? $this->params['simpleItemLinks'] : null;
    }

    public function getElements() {
        return $this->hasMany(Elements::className(), ['app_id' => 'id'])->indexBy('name');
    }

 /*   public function getCatlist() {
        $parents = Categories::find()->where(['app_id'=>$this->id,'parent_id'=>0])->orderBy('sort ASC')->all();
        return $catlist = count($parents) ? $this->getRelatedList($parents,[],'') : [];
    } */

    public function getCatlist() {

        if ($this->catlist === null) {

            $this->catlist = $this->processCatlist($this->getCategories()->select(['id','parent_id','name'])->asArray()->all());
        }

        return $this->catlist;
        
    }

    protected function processCatlist($categories,$parent_id = 0,$delimiter = null, $array=[]) {
        if (count($categories)) {
            foreach ($categories as $key=>$category) {
                if ($category['parent_id'] == $parent_id) {
                    $array[$category['id']] = (empty($delimiter)?'':$delimiter.' ').$category['name'];
                    unset($categories[$key]);
                    $array = $this->processCatlist($categories,$category['id'],$delimiter.'—',$array);
                }
            }
        }
        return $array;
    }

   /* protected function getRelatedList($items,$array,$level) {
        if (count($items)) {
            foreach ($items as $item) {
                $array[$item->id] = $level.' '.$item->name;
                if (count($item->related)) {
                    $array = $this->getRelatedList($item->related,$array,$level.'—');
                }
            }
        }
        return $array;
    } */

    
    public function getUrl() {
        return ['/'.$this->name.'/index'];
    }

}