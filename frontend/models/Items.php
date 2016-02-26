<?php

namespace worstinme\zoo\frontend\models;

use Yii;

class Items extends \yii\db\ActiveRecord
{
    public $elements;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    public function afterFind() {
        $this->registerElements();
        return parent::afterFind();
    }

    public function getApp() {
        return $this->hasOne(Applications::className(),['id'=>'app_id']);
    }

    public function registerElements() {
        return $this->elements = (new \yii\db\Query())->select('*')->from('{{%zoo_elements}}')
                ->where(['app_id'=>$this->app_id])
                ->indexBy('name')
                ->all();
    }

    public function getItemsElements() {
        return $this->hasMany(ItemsElements::className(),['item_id'=>'id'])->indexBy('element');
    }

    public function __get($name)
    {
        if (isset($this->elements[$name]) && !in_array($name, $this->attributes())) {
            return $this->getElementValue($name);
        } else {
            return parent::__get($name);
        }
    }

    public function getElementValue($name) 
    {
        if ($this->itemsElements[$name] !== null) {
            return $this->itemsElements[$name]->value_text;
        }
        elseif (is_array($this->elements[$name]) && array_key_exists('value', $this->elements[$name])) {
            return $this->elements[$name]['value'];
        }
        return null;
    }
}
