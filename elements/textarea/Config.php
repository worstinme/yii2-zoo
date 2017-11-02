<?php

namespace worstinme\zoo\elements\textarea;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-align-left';


    public function getRules()
    {
        return [
            ['editor', 'integer'],
        ];
    }

    public function getLabels()
    {
        return [
            'editor' => Yii::t('backend', 'Редактор'),
        ];
    }


    public function getEditor()
    {
        return isset($this->owner->paramsArray['editor'])?$this->owner->paramsArray['editor']:0;
    }

    public function setEditor($a)
    {
        $params = $this->owner->paramsArray;
        $params['editor'] = $a; 
        return $this->owner->paramsArray = $params;
    }


}