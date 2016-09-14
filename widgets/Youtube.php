<?php

namespace worstinme\zoo\widgets;

use Yii;
use yii\helpers\Html;

class Youtube extends \yii\base\Widget
{
	public $code;

    public function run()
    {
        return Html::tag("div", Html::tag('iframe',null,[
        	'class'=>'uk-responsive-width',
        	'src'=>'https://www.youtube.com/embed/'.$this->code,
        	'width'=>'750',
        	'height'=>'410',
        	'allowfullscreen'=>true,
        	'frameborder'=>0,
        ]),['class'=>'uk-text-center']);
    } 

}