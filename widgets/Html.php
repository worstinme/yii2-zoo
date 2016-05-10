<?php

namespace worstinme\zoo\widgets;

use Yii;
use yii\helpers\Html;

class Html extends \yii\base\Widget
{
    public $content;

    public function run()
    {
        return $this->content;
    }   

}