<?php

namespace worstinme\zoo\models;

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
            ['name', 'unique', 'targetClass' => Applications::className(), 'message' => Yii::t('admin', 'Такое приложение уже есть')],
            ['name', 'string', 'min' => 2, 'max' => 255],
            
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            [['sort', 'state'], 'integer'],
            [['params','template'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'name' => Yii::t('admin', 'Системное имя приложения'),
            'title' => Yii::t('admin', 'Название приложения'),
            'sort' => Yii::t('admin', 'Sort'),
            'state' => Yii::t('admin', 'State'),
            'created_at' => Yii::t('admin', 'Created At'),
            'updated_at' => Yii::t('admin', 'Updated At'),
            'params' => Yii::t('admin', 'Params'),
            'types'=>Yii::t('admin', 'Типы материалов'),
        ];
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        return parent::afterFind();
    }

    public function getCatlist() {
        $parents = Categories::find()->where(['app_id'=>$this->id,'parent_id'=>0,'type'=>0])->orderBy('sort ASC')->all();
        return $catlist = count($parents) ? $this->getRelatedList($parents,$catlist,'') : [];
    }

    public function getTypelist() {
        $parents = Categories::find()->where(['app_id'=>$this->id,'parent_id'=>0,'type'=>1])->orderBy('sort ASC')->all();
        return $catlist = count($parents) ? $this->getRelatedList($parents,$catlist,'') : [];
    }

    public function getParentCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id'=>0,'type'=>0])->orderBy('sort ASC');
    }

    public function getTypes() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id'=>0,'type'=>1])->orderBy('sort ASC'); 
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->orderBy('sort ASC');
    }

    public function getUrl() {
        return \yii\helpers\Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/application','app'=>$this->id]);
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

    public function getFields() {
        return $this->hasMany(Fields::className(), ['id' => 'field_id'])
            ->viaTable('{{%zoo_fields_categories}}', ['app_id' => 'id'])->indexBy('id');
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