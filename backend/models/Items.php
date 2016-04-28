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

}
