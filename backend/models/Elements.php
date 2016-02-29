<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class Elements extends \yii\db\ActiveRecord
{

    public $value;

    private $test;

    public function getTest() {
        if ($this->test === null) {
            $this->test = rand();
        }
        return $this->test;
    }

    private $categories = [];

    private $icon = 'uk-icon-plus';

    public function getMultiple() {
        return isset($this->_multiple) ? $this->_multiple : false;
    }

    public static function tableName()
    {
        return '{{%zoo_elements}}';
    }

    public function rules()
    {
        $rules = [
            [['name', 'type','title'], 'required'],
            [['name', 'type','title'], 'string', 'max' => 255],

            ['name', 'match', 'pattern' => '#^[\w_]+$#i'],
            ['type', 'match', 'pattern' => '#^[\w_]+$#i'],

            [['name', 'app_id'], 'unique', 'targetAttribute' => ['name', 'app_id']],

            ['placeholder', 'string', 'max' => 255],

            ['categories','each','rule'=>['integer']],//,'when' => function($model) { return $model->allcategories == 0; }, ],
            ['types','each','rule'=>['number']],

            [['filter', 'required', 'allcategories','refresh'], 'integer'],

            [['params'],'safe'],

        ];

        if (isset($this->rules) && count($this->rules)) {
            return ArrayHelper::merge($rules, $this->rules);
        }
        else {
            return $rules;
        }
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);

        if ($this->type !== null  && is_file(Yii::getAlias('@worstinme/zoo').'/elements/'.$this->type.'/Config.php')) {
            $element = '\worstinme\zoo\elements\\'.$this->type.'\Config';
            $this->attachBehaviors([
                $element::className()          // an anonymous behavior
            ]);
        }

        return parent::afterFind();
    }

    public function attributeLabels()
    {
        $labels = [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Название поля (Label)'),
            'name' => Yii::t('backend', 'Системное название поля'),
            'type' => Yii::t('backend', 'Type'),
            'required' => Yii::t('backend', 'Обязательно для заполнения?'),
            'filter' => Yii::t('backend', 'Использовать в фильтре?'),
            'state' => Yii::t('backend', 'State'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'params' => Yii::t('backend', 'Params'),
            'placeholder'=>Yii::t('backend', 'Placeholder'),
            'categories'=>Yii::t('backend', 'Категории'),
            'allcategories'=>Yii::t('backend', 'Все категории'),
            'types'=>Yii::t('backend', 'Типы материалов'),
            'type'=>Yii::t('backend', 'Тип элемента'),
            'refresh'=>Yii::t('backend', 'Обновлять поле?'),
        ];

        if (isset($this->labels) && count($this->labels)) {
            return ArrayHelper::merge($labels, $this->labels);
        }
        else {
            return $labels;
        }
    }

    public function getIcon() {
        if (isset($this->iconClass)) {
            return \yii\helpers\Html::i('',['class'=>$this->iconClass]);
        }
        else {
            return \yii\helpers\Html::i('',['class'=>$this->icon]);
        }
    }

    public function getFormView() {
        return '@worstinme/zoo/elements/'.$this->type.'/_form';
    }

    public function getSettingsView() {
        return '@worstinme/zoo/elements/'.$this->type.'/_settings';
    }

    public function getParamsView() {
        return '@worstinme/zoo/elements/'.$this->type.'/_params';
    }

    //placeholder
    public function getPlaceholder() { 
        return isset($this->params['placeholder'])?$this->params['placeholder']:''; 
    }

    public function setPlaceholder($preview) { 
        $params = $this->params;
        $params['placeholder'] = $preview; 
        return $this->params = $params;
    }

    //refresh
    public function getRefresh() { 
        return isset($this->params['refresh'])?$this->params['refresh']:0; 
    }

    public function setRefresh($refresh) { 
        $params = $this->params;
        $params['refresh'] = $refresh; 
        return $this->params = $params;
    }

    //allcategories
    public function getAllcategories()
    {
        return isset($this->params['allcategories'])?$this->params['allcategories']:1; 
    }

    public function setAllcategories($a)
    {
        $params = $this->params;
        $params['allcategories'] = $a; 
        return $this->params = $params;
    }

    //categories
    public function getCategories() {
        if (!count($this->categories)) {
            $this->categories = (new \yii\db\Query())
                    ->select('category_id')
                    ->from('{{%zoo_elements_categories}}')
                    ->where(['element_id'=>$this->id])
                    ->column();
        }
        return $this->categories;
    }

    public function setCategories($array) {
        $this->categories = $array;
        return true;
    }

    public function getTypes() {
        return count($this->params['types'])?$this->params['types']:[]; 
    }

    public function setTypes($array) {
        $params = $this->params;
        $params['types'] = $array;
        return $this->params = $params;
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
        $db = Yii::$app->db;

        $db->createCommand()->delete('{{%zoo_elements_categories}}', ['element_id'=>$this->id])->execute();

        $this->params = Json::decode($this->params);

        if ($this->allcategories == 1) {
            $db->createCommand()->insert('{{%zoo_elements_categories}}', [
                    'element_id' => $this->id,
                    'category_id' => 0,
                ])->execute();
        }
        elseif (count($this->categories)) {
            foreach ($this->categories as $category) {
                $db->createCommand()->insert('{{%zoo_elements_categories}}', [
                        'element_id' => $this->id,
                        'category_id' => (int)$category,
                    ])->execute();
            }    
        }
        
        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()->delete('{{%zoo_elements_categories}}', ['element_id'=>$this->id])->execute();

    }

}