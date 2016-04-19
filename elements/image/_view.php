<?php

use yii\helpers\Html; 

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

if ($width > 0 && $height > 0) {
	$thumbnail = '/thumbnails/'.$width.'-'.$height.'/';
}

if(isset($model->{$attribute})) {

	$url =  == 1 ? true : false;

	$image = !empty($thumbnail)?Html::img($thumbnail.ltrim($model->{$attribute}[0],"/")):Html::img($model->{$attribute}[0]);

	echo !empty($params['asUrl']) && $params['asUrl'] ? Html::a($image, $model->url,['data-pjax'=>0]) : $image;

}