<?php

use yii\helpers\Html;
use worstinme\zoo\helpers\ImageHelper;

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

if(!empty($model->{$attribute})) {

	$image = $width > 0 && $height > 0
        ? ImageHelper::thumbnailImg('@webroot'.$model->{$attribute}, $width, $height, ImageHelper::THUMBNAIL_OUTBOUND, [], 100)
        : Html::img($model->{$attribute});

	if (isset($params['lightbox']) && $params['lightbox']) {
		\worstinme\uikit\assets\Lightbox::register($this);
		echo Html::a($image, $model->{$attribute} ,['data'=>['pjax'=>0,'uk-lightbox'=>true]]) ;
	}
	else {
		echo !empty($params['asUrl']) && $params['asUrl'] ? Html::a($image, $model->url,['data-pjax'=>0,'class'=>'uk-thumbnail']) : $image;
	}

}