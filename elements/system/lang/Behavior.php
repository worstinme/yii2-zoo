<?php

namespace worstinme\zoo\elements\system\lang;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
	public function rules()
	{
		return [
            [$this->attribute,'in','range'=>array_keys(Yii::$app->zoo->languages),'skipOnEmpty'=>true],
		];
	}

	public function attach($owner)
    {
        parent::attach($owner);

        if ($owner->isNewRecord && count($languages = array_keys(Yii::$app->zoo->languages))) {
            $this->setValue(array_shift($languages));
        }

    }
}