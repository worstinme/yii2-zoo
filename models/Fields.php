<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class Fields extends \yii\db\ActiveRecord
{

    private $categories = [];

    private $icon = 'uk-icon-plus';

    public static function tableName()
    {
        return '{{%zoo_fields}}';
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

        if ($this->type !== null  && is_file(Yii::getAlias('@worstinme/zoo').'/fields/'.$this->type.'/Element.php')) {
            $element = '\worstinme\zoo\fields\\'.$this->type.'\Element';
            $this->attachBehaviors([
                $element::className()          // an anonymous behavior
            ]);
        }

        return parent::afterFind();
    }

    public function attributeLabels()
    {
        $labels = [
            'id' => Yii::t('admin', 'ID'),
            'title' => Yii::t('admin', 'Название поля (Label)'),
            'name' => Yii::t('admin', 'Системное название поля'),
            'type' => Yii::t('admin', 'Type'),
            'required' => Yii::t('admin', 'Обязательно для заполнения?'),
            'filter' => Yii::t('admin', 'Использовать в фильтре?'),
            'state' => Yii::t('admin', 'State'),
            'created_at' => Yii::t('admin', 'Created At'),
            'updated_at' => Yii::t('admin', 'Updated At'),
            'params' => Yii::t('admin', 'Params'),
            'placeholder'=>Yii::t('admin', 'Placeholder'),
            'categories'=>Yii::t('admin', 'Категории'),
            'allcategories'=>Yii::t('admin', 'Все категории'),
            'types'=>Yii::t('admin', 'Типы материалов'),
            'type'=>Yii::t('admin', 'Тип элемента'),
            'refresh'=>Yii::t('admin', 'Обновлять поле?'),
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

    public function getFieldName() {
        return $this->alias;
    }

    public function getFormView() {
        return '@worstinme/zoo/fields/'.$this->type.'/_form';
    }

    public function getSettingsView() {
        return '@worstinme/zoo/fields/'.$this->type.'/_settings';
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
                    ->from('{{%zoo_fields_categories}}')
                    ->where(['field_id'=>$this->id])
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

        $db->createCommand()->delete('{{%zoo_fields_categories}}', ['field_id'=>$this->id])->execute();

        $this->params = Json::decode($this->params);

        if ($this->allcategories == 1) {
            $db->createCommand()->insert('{{%zoo_fields_categories}}', [
                    'field_id' => $this->id,
                    'category_id' => 0,
                ])->execute();
        }
        elseif (count($this->categories)) {
            foreach ($this->categories as $category) {
                $db->createCommand()->insert('{{%zoo_fields_categories}}', [
                        'field_id' => $this->id,
                        'category_id' => (int)$category,
                    ])->execute();
            }    
        }
        
        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()->delete('{{%zoo_fields_categories}}', ['field_id'=>$this->id])->execute();

    }

}