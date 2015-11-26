<?php

namespace worstinme\zoo\fields\textarea;

use Yii;
use yii\helpers\ArrayHelper;

class Fields extends \worstinme\zoo\models\Fields
{

	public $iconClass = 'uk-icon-align-left';
    public $alias = 'textarea';

	public function getFieldName() {
		return Yii::t('admin','Текстовое поле');
	}

	public function rules()
    {
        $rules = [
            ['editor', 'integer'],
        ];

        return ArrayHelper::merge(parent::rules(),$rules);
    }

    public function attributeLabels()
    {
    	$labels = [
            'editor'=>Yii::t('admin', 'Использовать CKEditor'),
        ];

        return ArrayHelper::merge(parent::attributeLabels(),$labels);
    }


    //editor

    public function getEditor() { 
        return isset($this->params['editor'])?$this->params['editor']:0; 
    }

    public function setEditor($editor) { 
        $params = $this->params;
        $params['editor'] = $editor; 
        return $this->params = $params;
    }

}