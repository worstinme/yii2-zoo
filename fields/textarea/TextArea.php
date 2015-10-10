<?php

namespace worstinme\zoo\fields\textarea;

use Yii;
use yii\helpers\ArrayHelper;

class TextArea extends \worstinme\zoo\models\Fields
{

	public $iconClass = 'uk-icon-align-left';

	public function getFieldName() {
		return Yii::t('admin','Текстовое поле');
	}

	public function rules()
    {
        $rules = [
            
        ];

        return ArrayHelper::merge(parent::rules(),$rules);
    }

    public function attributeLabels()
    {
    	$labels = [
            
        ];

        return ArrayHelper::merge(parent::attributeLabels(),$labels);
    }
}