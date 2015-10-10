<?php

namespace worstinme\zoo\fields\select;

use Yii;
use yii\helpers\ArrayHelper;

class Select extends \worstinme\zoo\models\Fields
{

	public $iconClass = 'uk-icon-list';

	public function getFieldName() {
		return Yii::t('admin','Выбор из списка');
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