<?php

namespace worstinme\zoo\elements\textarea;

use Yii;

class Element extends \worstinme\zoo\elements\BaseElement
{
    public function getRules()
    {
        return [
            ['editor', 'integer'],
            ['editor', 'default', 'value' => 1],
        ];
    }

    public function getLabels()
    {
        return [
            'editor' => Yii::t('zoo', Yii::t('zoo', 'ELEMENT_TEXTAREA_ENABLE_EDITOR')),
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/textarea/_settings';
    }

    public function getEditor()
    {
        return isset($this->paramsArray['editor']) ? $this->paramsArray['editor'] : 1;
    }

    public function setEditor($a)
    {
        $params = $this->paramsArray;
        $params['editor'] = $a;
        return $this->paramsArray = $params;
    }


}
