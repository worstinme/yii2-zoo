<?php

namespace worstinme\zoo\fields\textfield;

use Yii;
use yii\helpers\ArrayHelper;

class TextField extends \worstinme\zoo\models\Fields
{
	public $iconClass = 'uk-icon-header';

	public function getFieldName() {
		return Yii::t('admin','Строка');
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