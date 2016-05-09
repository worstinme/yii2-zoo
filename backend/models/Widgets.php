<?php

namespace worstinme\zoo\backend\models;

use Yii;


class Widgets extends \worstinme\zoo\models\Widgets
{
    public $widgetModel;
    
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['params'], 'string'],
            [['type', 'name', 'position', 'bound'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'position' => 'Position',
            'bound' => 'Bound',
            'params' => 'Params',
        ];
    }

    public function load($data, $formName = null)
    {
        if ($this->widgetModel !== null) {
            return $this->widgetModel->load($data, $formName) && parent::load($data, $formName);
        }
    }

    public function getParams() {

        return $this->params !== null ? \yii\helpers\Json::decode($this->params) : null;
        
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->params = \yii\helpers\Json::encode($this->widgetModel->getAttributes());
            return true;
        } else {
            return false;
        }
    }

}
