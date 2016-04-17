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

    //metaTitle
    public function getMetaTitle() {
        return !empty($this->params['metaTitle']) ? $this->params['metaTitle'] : null;
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

        $a = $this->app->name;

        if ($this->app_id == 1) {
            if ($this->parent !== null) {
                if ($this->parent->parent !== null) {
                    return ['/zoo/default/abc','a'=>$this->parent->parent->alias,'b'=>$this->parent->alias,'c'=>$this->alias];
                }
                else {
                    return ['/zoo/default/ab','a'=>$this->parent->alias,'b'=>$this->alias];
                }
            }
            else {
                return ['/zoo/default/a','a'=>$this->alias];
            }
        }
        elseif ($this->parent !== null) {
            if ($this->parent->parent !== null) {
                return ['/zoo/default/abcd','a'=>$a,'b'=>$this->parent->parent->alias,'c'=>$this->parent->alias,'d'=>$this->alias];
            }
            else {
                return ['/zoo/default/abc','a'=>$a,'b'=>$this->parent->alias,'c'=>$this->alias];
            }
        }
        else {
            return ['/zoo/default/ab','a'=>$a,'b'=>$this->alias];
        }

    }

}
