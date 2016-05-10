<?php

namespace worstinme\zoo\widgets;

use Yii;
use yii\helpers\Html;
use worstinme\zoo\backend\models\Widgets;

class Widget extends \yii\base\Widget
{
    public $id;

    public function run()
    {
    	if ($this->id !== null && ($widget = Widgets::findOne($this->id)) !== null) {
    		return Yii::$app->zoo->callWidget($widget);
    	}
    }   

}