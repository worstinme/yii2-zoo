<?php

namespace worstinme\zoo\fields\textarea;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Element extends \yii\base\Behavior
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
            'editor' => Yii::t('admin', 'Редактор CKEditor'),
        ];
    }


    public function getEditor()
    {
        return isset($this->owner->params['editor'])?$this->owner->params['editor']:0; 
    }

    public function setEditor($a)
    {
        $params = $this->owner->params;
        $params['editor'] = $a; 
        return $this->owner->params = $params;
    }


}