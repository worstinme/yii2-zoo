<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;

class Field extends \yii\db\ActiveRecord
{

    public $value;
    public $reload = false;


    public static function tableName()
    {
        return '{{%zoo_fields}}';
    }


    public function rules()
    {
        $rules = [];

        if ($this->required) {
            $rules[] = [
                            $this->name, 'required',
                            'when' => function($model) { return $model->reload != true; }, 
                            'whenClient' => "function (attribute, value) {
                                console.log('чек');
                                return $('#hidden-reload').val() != 'true';
                            }"
                        ];
        } 

        $rules[] = [[$this->name,'value'], 'safe'];

        return $rules;

    }


    public function __get($name)
    {   
        if ($this->name == $name) {
            $value = $this->value;
        }
        else {
            $value = parent::__get($name);
        }
        return $value;
    }  

    public function __set($name, $value)
    {
        if ($this->name == $name) {
            $this->value = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        return parent::afterFind();
    }

    public function getModel() {
        $model = new ItemFields;
        $model->field_id = $this->id;
        $model->name = $this->name;
        return $model;
    }

    public function getVariants() {
        return $this->params['variants'];
    }

    public function getParam($param) {
        if (isset($this->params[$param]) && $this->params[$param] !== null) {
            return $this->params[$param];
        }
        else return null;        
    }

    public function getLabel() {
        $params = Json::decode($this->params);

        if (!empty($params['label_form'])) {
            return $params['label_form'];
        }
        elseif (!empty($params['label_view'])) {
            return $params['label_view'];
        }
        else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'params' => 'Params',
            'template' => 'Template',
            'filter' => 'Filter',
            'required' => 'Required',
            'alias' => 'Alias',
        ];
    }


}
