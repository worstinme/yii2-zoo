<?php

namespace worstinme\zoo\frontend\models;

use Yii;


class Applications extends \yii\db\ActiveRecord
{
    private $param_;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_applications}}';
    }

    public function getParam() {
        if ($this->param_ === null) {
            $this->param_ = \yii\helpers\Json::decode($this->params);
        }
        return $this->param_;
    }

    public function getTemplate($name) {
        return isset($this->param[$name]) ? $this->param[$name] : [];
    }

    public function getUrl() {
        return ['/zoo/default/a','a'=>$this->name];
    }

    //view Path
    public function getViewPath() { 
        return isset($this->param['viewPath'])?$this->param['viewPath']:''; 
    }

    //frontpage
    public function getFrontpage() { 
        return isset($this->param['frontpage'])?$this->param['frontpage']:''; 
    }

    public function getCatlinks() { 
        return isset($this->param['catlinks'])?$this->param['catlinks']:''; 
    }

    //metaTitle
    public function getMetaTitle() {
        return isset($this->param['metaTitle']) ? $this->param['metaTitle'] : $this->name;
    }

    //metaKeywords
    public function getMetaKeywords() {
        return isset($this->param['metaKeywords']) ? $this->param['metaKeywords'] : '';
    }

    //metaDescription
    public function getMetaDescription() {
        return isset($this->param['metaDescription']) ? $this->param['metaDescription'] : '';
    }

    public function getElements() {
        return $this->hasMany(Elements::className(), ['app_id' => 'id'])->indexBy('name');
    }

    public function getRelatedCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where('{{%zoo_categories}}.parent_id = 0')->orderBy('sort ASC');
    }
}
