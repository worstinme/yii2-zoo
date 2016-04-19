<?php

namespace worstinme\zoo\models;

class ItemsQuery extends \yii\db\ActiveQuery
{
    public function init()
    {

    	$modelClass = $this->modelClass;

    	$this->from(['i'=>'{{%zoo_items}}']);
    	
    	if ($modelClass::ENVIRONMENT == 'frontend') {
    		$this->joinWith(['itemsElements','categories','app']);
    	}

        parent::init();
    }

}