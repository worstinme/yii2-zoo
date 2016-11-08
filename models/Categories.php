<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;

class Categories extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%zoo_categories}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            [
                'class' => \worstinme\zoo\behaviors\SluggableBehavior::className(),
                'uniqueValidator'=>['attributes'=>'alias','targetAttribute'=>['alias','lang']],
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'app_id'], 'required'],
            ['alias', 'match', 'pattern' => '#^[\w_-]+$#i'],
            [['parent_id', 'app_id', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'safe'],
            [['name', 'alias','image','preview','subtitle'], 'string', 'max' => 255],
            [['metaDescription','metaKeywords','content','intro','quote'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],
            ['lang','string','max'=>255,'skipOnEmpty'=>true],

            //defaults
            ['state', 'default', 'value' => 1],
            ['parent_id', 'default', 'value' => 0],

        ];
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

    public function getSubtitle() {
        return !empty($this->params['subtitle']) ? $this->params['subtitle'] : null;
    }

    public function setSubtitle($s) {
        $params = $this->params; $params['subtitle'] = $s;
        return $this->params = $params;
    }

    public function getImage() {
        return !empty($this->params['image']) ? $this->params['image'] : null;
    }

    public function setImage($s) {
        $params = $this->params; $params['image'] = $s;
        return $this->params = $params;
    }

    public function getQuote() {
        return !empty($this->params['quote']) ? $this->params['quote'] : null;
    }

    public function setQuote($s) {
        $params = $this->params; $params['quote'] = $s;
        return $this->params = $params;
    }

    public function getPreview() {
        return !empty($this->params['preview']) ? $this->params['preview'] : null;
    }

    public function setPreview($s) {
        $params = $this->params; $params['preview'] = $s;
        return $this->params = $params;
    }

    public function getMetaTitle() {
        return !empty($this->params['metaTitle']) ? $this->params['metaTitle'] : $this->name;
    }

    //metaTitle
    public function setMetaTitle($s) {
        $params = $this->params; $params['metaTitle'] = $s;
        return $this->params = $params;
    }

    //metaKeywords
    public function getMetaKeywords() {
        return !empty($this->params['metaKeywords']) ? $this->params['metaKeywords'] : null;
    }

    public function setMetaKeywords($s) {
        $params = $this->params; $params['metaKeywords'] = $s;
        return $this->params = $params;
    }

    //metaDescription
    public function getMetaDescription() {
        return !empty($this->params['metaDescription']) ? $this->params['metaDescription'] : null;
    }

    public function setMetaDescription($s) {
        $params = $this->params; $params['metaDescription'] = $s;
        return $this->params = $params;
    }

    public function getContent() {
        return !empty($this->params['content']) ? $this->params['content'] : null;
    }

    public function setContent($s) {
        $params = $this->params; $params['content'] = $s;
        return $this->params = $params;
    }

    public function getIntro() {
        return !empty($this->params['intro']) ? $this->params['intro'] : null;
    }

    public function setIntro($s) {
        $params = $this->params; $params['intro'] = $s;
        return $this->params = $params;
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

    public function getItems()
    {
        return $this->hasMany(Items::className(),['id'=>'item_id'])->viaTable('{{%zoo_items_categories}}',['category_id'=>'id']);
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

        if ($this->parent_id != null && $this->parent !== null) {
            if ($this->parent->parent_id != null && $this->parent->parent !== null) {
                $url = ['/'.$this->app->name.'/abc','a'=>$this->parent->parent->alias,'b'=>$this->parent->alias,'c'=>$this->alias];
            }
            else {
                $url =  ['/'.$this->app->name.'/ab','a'=>$this->parent->alias,'b'=>$this->alias];
            }
        }
        else {
            $url =  ['/'.$this->app->name.'/a','a'=>$this->alias];
        }

        if (!empty($this->lang)) {
            $url['lang'] = $this->lang;
        }

        return $url;

    }

    public function getBreadcrumbs($selfUrl = false) {
        $crumbs = $selfUrl ? [['label'=>$this->name,'url'=>$this->url]] : [$this->name];
        $parent = $this->parent;
        while ($parent !== null) {
            $crumbs[] = ['label' => $parent->name, 'url' =>  $parent->url]; 
            $parent = $parent->parent;
        }
        $crumbs[] = ['label'=>$this->app->title,'url'=>$this->app->url];
        return array_reverse($crumbs);
    }

    public function afterDelete()
    {
        $db = Yii::$app->db;

        $db->createCommand()->delete('{{%zoo_items_categories}}', ['category_id'=>$this->id])->execute();
        $db->createCommand()->delete('{{%zoo_elements_categories}}', ['category_id'=>$this->id])->execute();
        $db->createCommand()->update('{{%zoo_categories}}',['parent_id'=>$this->parent_id], ['parent_id'=>$this->id])->execute();
        
        parent::afterDelete();
        
    }

}
