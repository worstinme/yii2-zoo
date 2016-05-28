<?php

use yii\helpers\Html; 
use yii\imagine\Image;
use Imagine\Image\Box;

$type = !empty($params['type']) ?  $params['type'] : null;



if ($type == 'wall') {
	echo $this->render('_wall',['params'=>$params,'model'=>$model,'attribute'=>$attribute]);
}
elseif($type == 'slideshow' && count($model->{$attribute}) > 1) {
	echo $this->render('_slideshow',['params'=>$params,'model'=>$model,'attribute'=>$attribute]);
}
elseif($type == 'gallery' && count($model->{$attribute}) > 1) {
	echo $this->render('_gallery',['params'=>$params,'model'=>$model,'attribute'=>$attribute]);
}
elseif (isset($model->{$attribute}[0])) {

	$width = isset($params['width']) ? (int)$params['width'] : null;
	$height = isset($params['height']) ? (int)$params['height'] : null;

	if ($width > 0 && $height > 0) {
		$thumbnail = '/thumbnails/'.$width.'-'.$height.'/';
	}
 
	$url = isset($params['asUrl']) && $params['asUrl'] == 1 ? true : false;
	$preview = isset($params['preview']) && $params['preview'] == 1 ? true : false;

	if ($preview) {

		$image = !empty($thumbnail)?Html::img($thumbnail.ltrim($model->{$attribute}[0],"/")):Html::img($model->{$attribute}[0]);

		echo $url ? Html::a($image, $model->url,['data-pjax'=>0]) : $image;

	} else {

		foreach ($model->{$attribute} as $img_url) {

			$image = !empty($thumbnail)?Html::img($thumbnail.ltrim($img_url,"/")):Html::img($img_url);

			echo $url ? Html::a($image, $model->url,['data-pjax'=>0]) : $image;
			
		}
		
	}

}