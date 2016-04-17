<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

class Categories extends \worstinme\zoo\models\Categories
{

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'app_id'], 'required'],
            ['alias', 'match', 'pattern' => '#^[\w_-]+$#i'],
            [['parent_id', 'app_id', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            [['name', 'alias','image'], 'string', 'max' => 255],
            [['metaDescription','metaKeywords','content','intro'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],

            //defaults
            ['state', 'default', 'value' => 1],
            ['parent_id', 'default', 'value' => 0],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'alias' => Yii::t('backend', 'Алиас'),
            'name' => Yii::t('backend', 'Название'),
            'parent_id' => Yii::t('backend', 'Родитель'),
            'app_id' => Yii::t('backend', 'App ID'),
            'sort' => Yii::t('backend', 'Sort'),
            'state' => Yii::t('backend', 'Опубликовано?'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'params' => Yii::t('backend', 'Params'),
            'preContent' =>  Yii::t('backend', 'Верхнее описание категории'),
            'content' =>  Yii::t('backend', 'Нижнее описание категории'),
            'image'=>Yii::t('backend', 'Изображение категории'),
        ];
    }

    public function setMetaTitle($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['metaTitle'] = $s;
        return $this->params = Json::encode($params);
    }

    public function setImage($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['image'] = $s;
        return $this->params = Json::encode($params);
    }

    public function setMetaKeywords($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['metaKeywords'] = $s;
        return $this->params = Json::encode($params);
    }

    //metaDescription
    public function getMetaDescription() {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['metaDescription']) ? $params['metaDescription'] : '';
    }
    public function setMetaDescription($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['metaDescription'] = $s;
        return $this->params = Json::encode($params);
    }

    //metaDescription
    public function getContent() {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['content']) ? $params['content'] : '';
    }
    public function setContent($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['content'] = $s;
        return $this->params = Json::encode($params);
    }

    //metaDescription
    public function getIntro() {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['intro']) ? $params['intro'] : '';
    }
    public function setIntro($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['intro'] = $s;
        return $this->params = Json::encode($params);
    }

    public function getTemplate($name) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['templates']) && isset($params['templates'][$name]) ? $params['templates'][$name] : [];
    }

    public function getRelated()
    {
        return $this->hasMany(Categories::className(), ['parent_id' => 'id'])->orderBy('sort ASC');
    } 

    public function getFrontendCategory()
    {
        return $this->hasOne(\worstinme\zoo\frontend\models\Categories::className(), ['id' => 'id']);
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
        return yii\helpers\Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/update-category','app'=>$this->app_id,'category'=>$this->id]);
    }

    public function afterDelete()
    {
        $db = Yii::$app->db;

        $db->createCommand()->delete('{{%zoo_items_categories}}', ['category_id'=>$this->id])->execute();
        $db->createCommand()->delete('{{%zoo_elements_categories}}', ['category_id'=>$this->id])->execute();
        $db->createCommand()->update('zoo_categories',['parent_id'=>0], ['parent_id'=>$this->id])->execute();
        
        parent::afterDelete();
        
    }
}
