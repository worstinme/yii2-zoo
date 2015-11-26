<?php

namespace worstinme\zoo\fields\category;

use Yii;
use yii\helpers\ArrayHelper;

class Fields extends \worstinme\zoo\models\Fields
{

	public $iconClass = 'uk-icon-th-list';
    
    public $alias = 'category';

	public function getFieldName() {
		return Yii::t('admin','Выбор категории');
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