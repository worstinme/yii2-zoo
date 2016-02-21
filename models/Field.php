<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\Html;

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
                            'whenClient' => "function (attribute, value) { if ($('#hidden-reload').val() != 'true') {console.log('Проверка поля ".$this->name."');return true; } else return false; }"
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

    public function addValidators($view) {

        $inputID = Html::getInputId($this, $this->name);

        foreach ($this->getActiveValidators($this->name) as $validator) {
            $js = $validator->clientValidateAttribute($this, $this->name, $view); 
            if ($js != '') {
                if ($validator->whenClient !== null) {
                    $js = "if (({$validator->whenClient})(attribute, value)) { $js }";
                }
                $validators[] = $js;
            }   
        }

        $options = Json::htmlEncode([
            'id' => $inputID,
            'name' => $this->name,
            'container' => ".field-".$this->id,
            'input' => "#$inputID",
            'validate' => new \yii\web\JsExpression("function (attribute, value, messages, deferred, \$form) {" . implode('', $validators) . '}'),
            'validateOnChange' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validationDelay' => 500,
            'encodeError' => true,
            'error' => '.uk-form-help-block.uk-text-danger',
        ]);

        return "$('#form').yiiActiveForm('add', $options);";
    }

}
