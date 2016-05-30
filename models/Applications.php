<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;

class Applications extends \yii\db\ActiveRecord
{
    public $lang;
    private $catlist;
    private $templatesConfig;

    public static function tableName()
    {
        return '{{%zoo_applications}}';
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
        $categories = $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id'=>0,'state'=>1]);
        if ($this->lang !== null) 
            $categories->andWhere(['lang'=>$this->lang]);
        return $categories->orderBy('sort ASC');
    }

    public function getCategories() {
        $categories = $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['state'=>1]);
        if ($this->lang !== null) 
            $categories->andWhere(['lang'=>Yii::$app->zoo->lang]);
        return $categories->orderBy('sort ASC')->inverseOf('app');
    }

    public function getItems() {
        return $this->hasMany(Items::className(), ['app_id' => 'id'])->inverseOf('app')->where([Items::tablename().'.state'=>1]);
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

    public function getTemplatesConfig($name = null) {
        if ($this->templatesConfig === null) {

            $default = ["form"=>[],"full"=>[],"teaser"=>[],"related"=>[],"submission"=>[]];
            $configFile = Yii::getAlias('@app/views/'.$this->name.'/templates.json');
            if (is_file($configFile)) {
                $this->templatesConfig = array_merge($default,Json::decode(file_get_contents($configFile)));
            }
            else {
                $this->templatesConfig = $default;
            }
        }
        return $name === null ? $this->templatesConfig : (!empty($this->templatesConfig[$name]) ? $this->templatesConfig[$name] : []);
    }

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

        if (!empty(Yii::$app->zoo->languages)) {
            return ['/'.$this->name.'/index','lang'=>Yii::$app->zoo->lang];
        }
        return ['/'.$this->name.'/index'];
    }

}