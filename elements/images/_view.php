<?php

use yii\helpers\Html; 

$url = isset($params['asUrl']) && $params['asUrl'] == 1 ? true : false;
$preview = isset($params['preview']) && $params['preview'] == 1 ? true : false;

if ($preview) {

	$image  = Html::img($model->{$attribute}[0]);

	echo $url ? Html::a($image, $model->url) : $image;

} else {
	foreach ($model->{$attribute} as $img_url) {
		echo $url ? Html::a(Html::img($img_url), $model->url) : Html::img($img_url);
	}
}
