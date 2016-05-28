<?php

namespace worstinme\zoo\widgets;

use Yii;

class Html extends \yii\base\Widget
{
    public $content;

    public function run()
    {
        return $this->content;
    }   

}