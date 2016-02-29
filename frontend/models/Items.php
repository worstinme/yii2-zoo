<?php

namespace worstinme\zoo\frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

class Items extends \yii\db\ActiveRecord
{
    public $elements_;
    public $values = [];
    private $param_;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    public function afterFind() {
        $this->attachBehaviors();
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

    public function attachBehaviors() {
        
        $this->elements_ = array_unique(ArrayHelper::getColumn($this->elements,'type'));
               
        foreach ($this->elements_ as $behavior) {
            if (is_file(Yii::getAlias('@worstinme/zoo/elements/'.$behavior.'/Element.php'))) {
                $behavior_class = '\worstinme\zoo\elements\\'.$behavior.'\Element';
                $this->attachBehavior($behavior,$behavior_class::className());
            }
        }

        return true;
    }

    public function __get($name)
    { 
        if (!in_array($name, $this->attributes()) && $this->elements[$name] !== null && ($behavior = $this->getBehavior($this->elements_[$name])) !== null) {
            return $behavior->getValue($name);
        } else {
            return parent::__get($name);
        }
    } 

    public function getParam() {
        if ($this->param_ === null) {
            $this->param_ = \yii\helpers\Json::decode($this->params);
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

    public function getTemplate($name) {
        return isset($this->param['templates']) && isset($this->param['templates'][$name]) ? $this->param['templates'][$name] : $this->app->getTemplate($name);
    }

    public function getUrl() {
        return '#';
    }
}
