<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

class Applications extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%zoo_applications}}';
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
            ['name', 'required'],
            ['name', 'match', 'pattern' => '#^[\w_]+$#i'],
            ['name', 'unique', 'targetClass' => Applications::className(), 'message' => Yii::t('backend', 'Такое приложение уже есть')],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['viewPath', 'string', 'max' => 255],
            
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            [['sort', 'state'], 'integer'],
            [['params','template','frontpage'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Системное имя приложения'),
            'title' => Yii::t('backend', 'Название приложения'),
            'sort' => Yii::t('backend', 'Sort'),
            'state' => Yii::t('backend', 'State'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'params' => Yii::t('backend', 'Params'),
        ];
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        return parent::afterFind();
    }

    public function getCatlist() {
        $parents = Categories::find()->where(['app_id'=>$this->id,'parent_id'=>0])->orderBy('sort ASC')->all();
        return $catlist = count($parents) ? $this->getRelatedList($parents,$catlist,'') : [];
    }

    public function getParentCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id'=>0])->orderBy('sort ASC');
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->orderBy('sort ASC');
    }

    public function getUrl() {
        return \yii\helpers\Url::toRoute(['/'.Yii::$app->controller->module->id.'/items/index','app'=>$this->id]);
    }

   /* public function getTypes() {
        return isset($this->params['types'])?$this->params['types']:[];
    }

    public function setTypes($array) {
        $params = $this->params;
        foreach ($array as $key => $value) { if (empty($value)) unset($array[$key]); }
        $params['types'] = $array;
        return $this->params = $params;
    }*/

    public function setTemplate($name,$rows) {
        $params = $this->params; 
        foreach ($rows as $key=>$row) {
            if (!count($row['items'])) {
                unset($rows[$key]);
            }
        }
        $params[$name] = $rows;; 
        return $this->params = $params;
    }

    public function getTemplate($name = null) {
        return isset($this->params[$name]) ? $this->params[$name] : [];
    }

    //frontpage
    public function getFrontpage() { 
        return isset($this->params['frontpage'])?$this->params['frontpage']:''; 
    }

    public function setFrontpage($preview) { 
        $params = $this->params;
        $params['frontpage'] = $preview; 
        return $this->params = $params;
    }

    //view Path
    public function getViewPath() { 
        return isset($this->params['viewPath'])?$this->params['viewPath']:''; 
    }

    public function setViewPath($preview) { 
        $params = $this->params;
        $params['viewPath'] = $preview; 
        return $this->params = $params;
    }

    public function getElements() {
        return $this->hasMany(Elements::className(), ['app_id' => 'id'])->indexBy('name');
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

    protected function getRelatedList($items,$array,$level) {
        if (count($items)) {
            foreach ($items as $item) {
                $array[$item->id] = $level.' '.$item->name;
                if (count($item->related)) {
                    $array = $this->getRelatedList($item->related,$array,$level.'—');
                }
            }
        }
        return $array;
    }

}