<?php

namespace worstinme\zoo\backend\models;

use Yii;
use \yii\db\ActiveQuery;

class Items extends \worstinme\zoo\models\Items
{

    public function rules() 
    {
        $rules  = [
            [['flag','state',],'integer'],
        ];

        return $rules = array_merge(parent::rules(),$rules);
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(),['id'=>'category_id'])
            ->viaTable('{{%zoo_items_categories}}',['item_id'=>'id']);
    }

}
