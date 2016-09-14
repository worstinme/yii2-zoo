<?php

namespace worstinme\zoo\widgets;

use Yii;
use yii\helpers\Html;
use worstinme\zoo\models\Items;

class Teaser extends \yii\base\Widget
{
    public $id;
    public $ids;

    public function run()
    {
    	if ($this->id !== null && ($item = Items::findOne($this->id)) !== null) {

    		$teaser = \worstinme\zoo\helpers\TemplateHelper::render($item,'teaser');

    		return "<div class='item item-teaser'>".$teaser."</div>";
    	}

    	if ($this->ids !== null) {

    		$ids = explode(",",$this->ids);
    		$items = Items::find()->where(['id'=>$ids])->all();

    		$html = null;

    		if (count($items)) {
    			
    			$html .= '<div class="uk-grid uk-grid-width-medium-1-2 uk-grid-large uk-grid-match" data-uk-grid-margin>';

    			foreach ($items as $item) {
    				$teaser = \worstinme\zoo\helpers\TemplateHelper::render($item,'teaser');
    				$html .= "<div><div class='item item-teaser'>".$teaser."</div></div>";
    			}

    			$html .= '</div>';
    		}

    		return $html;
    	}

    	return null;
    }   

}