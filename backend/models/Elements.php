<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class Elements extends \worstinme\zoo\models\Elements
{

    private $categories = [];

    private $icon = 'uk-icon-plus';

    public static function tableName()
    {
        return '{{%zoo_elements}}';
    }

    public function rules()
    {
        $rules = [

            [['name', 'type','label'], 'required'],
            [['name', 'type','label'], 'string', 'max' => 255],

            [['adminHint'], 'string'],

            ['name', 'match', 'pattern' => '#^[\w_]+$#i'],
            ['type', 'match', 'pattern' => '#^[\w_]+$#i'],

            [['name', 'app_id'], 'unique', 'targetAttribute' => ['name', 'app_id']],

            ['categories','each','rule'=>['integer']],//,'when' => function($model) { return $model->allcategories == 0; }, ],

            [['filter','adminFilter','search', 'required', 'allcategories','refresh','sorter','ownColumn'], 'integer'],

            [['related'], 'match', 'pattern' => '#^[\w_]+$#i'],

            [['params'],'safe'],

        ];

        if (isset($this->rules) && count($this->rules)) {
            return ArrayHelper::merge($rules, $this->rules);
        }
        else {
            return $rules;
        }
    }

    public function attributeLabels()
    {
        $labels = [
            'id' => Yii::t('zoo', 'ID'),
            'title' => Yii::t('zoo', 'Название поля (Label)'),
            'name' => Yii::t('zoo', 'Системное название поля'),
            'type' => Yii::t('zoo', 'Type'),
            'required' => Yii::t('zoo', 'Обязательно для заполнения?'),
            'filter' => Yii::t('zoo', 'Использовать в фильтре?'),
            'params' => Yii::t('zoo', 'Params'),
            'placeholder'=>Yii::t('zoo', 'Placeholder'),
            'categories'=>Yii::t('zoo', 'Категории'),
            'allcategories'=>Yii::t('zoo', 'Все категории'),
            'types'=>Yii::t('zoo', 'Типы материалов'),
            'type'=>Yii::t('zoo', 'Тип элемента'),
            'refresh'=>Yii::t('zoo', 'Обновлять поле?'),
            'sorter'=>'Использовать поле в сортировке',
            'adminHint'=>Yii::t('zoo', 'Подсказка к полю в форме админки'),
            'ownColumn'=>Yii::t('zoo','Выделить отдельную колонку'),
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

    public function getApp() {
        return $this->hasOne(Applications::className(),['id'=>'app_id']);
    }

    public function renderParams($params) {
        return '';
    }

    public function setAdminHint($s) { 
        $params = $this->params;
        $params['adminHint'] = $s; 
        return $this->params = $params;
    }

    public function setSorter($s) { 
        $params = $this->params;
        $params['sorter'] = $s; 
        return $this->params = $params;
    }

    public function setRelated($related) { 
        $params = $this->params;
        $params['related'] = $related; 
        return $this->params = $params;
    }


    public function setRequired($related) { 
        $params = $this->params;
        $params['required'] = $related; 
        return $this->params = $params;
    }

    public function setRefresh($refresh) { 
        $params = $this->params;
        $params['refresh'] = $refresh; 
        return $this->params = $params;
    }

    public function setFilter($filter) { 
        $params = $this->params;
        $params['filter'] = $filter; 
        return $this->params = $params;
    }

    public function setAdminFilter($filter) { 
        $params = $this->params;
        $params['adminFilter'] = $filter; 
        return $this->params = $params;
    }

    public function setSearch($filter) {
        $params = $this->params;
        $params['search'] = $filter;
        return $this->params = $params;
    }

    public function setAllcategories($a)
    {
        $params = $this->params;
        $params['allcategories'] = $a;
        return $this->params = $params;
    }

    public function setOwnColumn($filter) {
        $params = $this->params;
        $params['ownColumn'] = $filter;
        return $this->params = $params;
    }

    public function setOwnColumn($a)
    {
        $params = $this->params;
        $params['ownColumn'] = $a;
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
    }


    public function afterSave($insert, $changedAttributes)
    {
        $db = Yii::$app->db;

        if (!$insert) {
            $db->createCommand()->delete('{{%zoo_elements_categories}}', ['element_id'=>$this->id])->execute();
        }

        $params = Json::decode($this->params);

        $allcategories = isset($params['allcategories']) ? $params['allcategories'] : 1;


        if ($allcategories) {
            $db->createCommand()->insert('{{%zoo_elements_categories}}', [
                    'element_id' => $this->id,
                    'category_id' => 0,
                ])->execute();
        }
        elseif (is_array($this->categories) && count($this->categories)) {
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