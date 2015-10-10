<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;

class Fields extends \yii\db\ActiveRecord
{

    protected $alias = null;
    protected $params;
    protected $categories = [];

    public $iconClass = 'uk-icon-plus';

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%zoo_fields}}';
    }

    public function init()
    {
        $this->type = $this->getAlias();
        parent::init();
    }

    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            ['name', 'string', 'max' => 255],
            ['name', 'match', 'pattern' => '#^[\w_]+$#i'],

            ['placeholder', 'string', 'max' => 255],

            ['categories','each','rule'=>['integer']],
            ['types','each','rule'=>['number']],

            [['title', 'type'], 'string', 'max' => 255],

            [['filter', 'required'], 'integer'],

        ];
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        return parent::afterFind();
    }

    public function attributeLabels()
    {
        return [
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
            'types'=>Yii::t('admin', 'Типы материалов'),
        ];
    }

    public function getAlias() {
        if ($this->alias === null) {
            $this->alias = strtolower(array_pop(explode('\\',$this->className())));
        }
        return $this->alias;
    }

    public function getFieldName() {
        return $this->alias;
    }

    public function getFormView() {
        return '@worstinme/zoo/fields/'.$this->alias.'/_form';
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

        if (count($this->categories)) {
            foreach ($this->categories as $category) {
                $db->createCommand()->insert('{{%zoo_fields_categories}}', [
                        'field_id' => $this->id,
                        'category_id' => (int)$category,
                        'app_id' => Yii::$app->controller->module->app->id,
                    ])->execute();
            }    
        }
        
        $this->params = Json::decode($this->params);

        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()->delete('{{%zoo_fields_categories}}', ['field_id'=>$this->id])->execute();

    }

}