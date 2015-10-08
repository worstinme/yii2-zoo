<?php

namespace worstinme\zoo\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
            [['params'], 'string'],
            ['title', 'string', 'max' => 255],
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
        ];
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
        return \yii\helpers\Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/application','app'=>$this->id]);
    }

    /////////////////////////////////////////////////////////

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