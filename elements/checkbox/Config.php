<?php

namespace worstinme\zoo\elements\checkbox;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-header';

    public function init() {

        parent::init();

    }

    public function getRules()
    {
        return [
            ['checkboxLabel', 'string','max'=>255],
        ];
    }

    public function getLabels()
    {
        return [
            'checkboxLabel' => Yii::t('zoo', 'CHECKBOX_LABEL'),
        ];
    }


    public function getCheckboxLabel()
    {
        return isset($this->owner->paramsArray['checkboxLabel'])?$this->owner->paramsArray['checkboxLabel']:null;
    }

    public function setCheckboxLabel($a)
    {
        $params = $this->owner->paramsArray;
        $params['checkboxLabel'] = $a;
        return $this->owner->paramsArray = $params;
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/checkbox/_settings';
    }


}