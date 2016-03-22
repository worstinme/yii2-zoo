<?php

namespace worstinme\zoo\frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use worstinme\zoo\backend\models\Items;

class Items extends Items
{
    public $values = [];
    private $param_;

    public function attributeLabels()
    {
        return [];
    }

    public function afterFind() { 
        $this->regBehaviors();
        return parent::afterFind();
    }

    public function getApp() {
        return $this->hasOne(Applications::className(),['id'=>'app_id']);
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(),['id'=>'category_id'])
            ->viaTable('{{%zoo_items_categories}}',['item_id'=>'id']);
    }

    public function getElements() {
        return $this->hasMany(Elements::className(),['app_id'=>'app_id'])->indexBy('name');
    }

    public function getItemsElements() {
        return $this->hasMany(ItemsElements::className(),['item_id'=>'id']);
    }

    public function regBehaviors() {
        
        $elements = array_unique(ArrayHelper::getColumn($this->elements,'type'));
               
        foreach ($elements as $behavior) {
            if (is_file(Yii::getAlias('@worstinme/zoo/elements/'.$behavior.'/Element.php'))) {
                $behavior_class = '\worstinme\zoo\elements\\'.$behavior.'\Element';
                $this->attachBehavior($behavior,$behavior_class::className());
            }
        }

        return true;
    }

    public function __get($name)
    { 
        if (!in_array($name, $this->attributes())
                && $name != 'elements'
                && isset($this->elements[$name])
                && $this->elements[$name] !== null 
                && ($behavior = $this->getBehavior($this->elements[$name]->type)) !== null) {
            return $behavior->getValue($name);
        } else {
            return parent::__get($name);
        }
    } 

    public function getParam() {
        if ($this->param_ === null) {
            $this->param_ = $this->params !== null  && !empty($this->params) ? \yii\helpers\Json::decode($this->params,true) : [];
        }
        return $this->param_;
    }

        //metaTitle
    public function getMetaTitle() {
        return isset($this->params['metaTitle']) ? $this->params['metaTitle'] : $this->name;
    }

    //metaKeywords
    public function getMetaKeywords() {
        return isset($this->params['metaKeywords']) ? $this->params['metaKeywords'] : '';
    }

    //metaDescription
    public function getMetaDescription() {
        return isset($this->params['metaDescription']) ? $this->params['metaDescription'] : '';
    }

    public function getTemplate($name = 'full') {
        return isset($this->param['templates']) && isset($this->param['templates'][$name]) ? $this->param['templates'][$name] : $this->app !==null ? $this->app->getTemplate($name) : null;
    }

    public function getParentCategory() {
        return $this->hasOne(Categories::className(),['id'=>'category_id'])
            ->viaTable('{{%zoo_items_categories}}',['item_id'=>'id'])->where(['{{%zoo_categories}}.parent_id'=>0]);
    }

    public function getUrl() {

        if ($this->app_id == 1) {  
            if ($this->app->catlinks && $this->parentCategory !== null) {

                return ['/zoo/default/ab','a'=> $this->parentCategory->alias,'b'=> !empty($this->alias) ? $this->alias :  $this->id ];
            }
            else {
                return ['/zoo/default/a','a'=> !empty($this->alias) ? $this->alias :  $this->id ];
            }            
        }
        else {
            return ['/zoo/default/ab','a'=>$this->app->name,'b'=>!empty($this->alias) ? $this->alias :  $this->id];
        }
        
    }
}
