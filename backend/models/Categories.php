<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
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
            ['parent_id', 'default', 'value' => 0],
            [['name', 'alias'], 'string', 'max' => 255],

            [['metaDescription','metaKeywords','content','preContent'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],

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
            'state' => Yii::t('backend', 'State'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'params' => Yii::t('backend', 'Params'),
            'preContent' =>  Yii::t('backend', 'Верхнее описание категории'),
            'content' =>  Yii::t('backend', 'Нижнее описание категории'),
        ];
    }

        //metaTitle
    public function getMetaTitle() {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['metaTitle']) ? $params['metaTitle'] : '';
    }
    public function setMetaTitle($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['metaTitle'] = $s;
        return $this->params = Json::encode($params);
    }

    //metaKeywords
    public function getMetaKeywords() {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['metaKeywords']) ? $params['metaKeywords'] : '';
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
    public function getPreContent() {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params['preContent']) ? $params['preContent'] : '';
    }
    public function setPreContent($s) {
        $params = $this->params !== null && !empty($this->params) ? Json::decode($this->params) : [];
        $params['preContent'] = $s;
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
}
