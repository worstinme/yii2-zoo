<?php

namespace worstinme\zoo\widgets;

use Yii;
use yii\helpers\Html as hHtml;

class Review extends \yii\base\Widget
{
    public $content;
    public $image;
    public $subtitle;
    public $title;

    public function run()
    {
    	$html = '<div class="review"><div class="uk-grid" data-uk-grid-margin>';

    	if (!empty($this->image)) {
    		$html .= hHtml::tag("div", hHtml::img($this->image), ['class' => 'review-image uk-width-medium-1-5']);
    	}

    	$html .= '<div class="review-content uk-width-medium-4-5 uk-flex uk-flex-middle uk-flex-center"><div class="uk-panel">';

    	if (!empty($this->title)) {
    		$html .= hHtml::tag("div", $this->title,['class'=>'title']);
    	}

    	if (!empty($this->subtitle)) {
    		$html .= hHtml::tag("div", $this->subtitle,['class'=>'subtitle']);
    	}

    	if (!empty($this->content)) {
    		$html .= hHtml::tag("div", $this->content,['class'=>'content']);
    	}

    	$html .= '</div></div>';
    	$html .= '</div></div>';

        return $html;
    }   

}