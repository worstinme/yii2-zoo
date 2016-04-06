<?php

use yii\helpers\Html; 
use yii\imagine\Image;
use Imagine\Image\Box;

worstinme\uikit\assets\Slideset::register($this);

?>

<?php if (isset($params['gallery']) && $params['gallery'] == 1) : ?>
	
	<?php if (count($model->{$attribute}) > 1): ?>

	<ul id="slider" class="uk-switcher">
		<?php foreach ($model->{$attribute} as $img_url): ?>
			<li><?=Html::img($img_url);?></li>
		<?php endforeach ?>
	</ul>

	<div data-uk-slideset="{default: 4}">
	    <ul class="uk-grid uk-grid-collapse uk-slideset" data-uk-switcher="{connect:'#slider'}">
	        <?php foreach ($model->{$attribute} as $img_url): ?>
				<li><?=Html::img('/thumbnails/160-120/'.ltrim($img_url,"/"));?></li>
			<?php endforeach ?>
	    </ul>
	    <a href=""data-uk-slideset-item="previous"></a>
	    <a href=""data-uk-slideset-item="next"></a>
	</div>

	<?php elseif(isset($model->{$attribute}[0])):  ?>
		<?=Html::img($model->{$attribute}[0]);?>
	<?php endif ?>

<?php elseif(isset($model->{$attribute}[0])): 

	$url = isset($params['asUrl']) && $params['asUrl'] == 1 ? true : false;
	$preview = isset($params['preview']) && $params['preview'] == 1 ? true : false;

	$width = isset($params['width']) ? (int)$params['width'] : null;
	$height = isset($params['height']) ? (int)$params['height'] : null;

	if ($width > 0 && $height > 0) {
		
		$thumbnail = '/thumbnails/'.$width.'-'.$height.'/';
	}

	if ($preview) {

		$image = !empty($thumbnail)?Html::img($thumbnail.ltrim($model->{$attribute}[0],"/")):Html::img($model->{$attribute}[0]);

		echo $url ? Html::a($image, $model->url,['data-pjax'=>0]) : $image;

	} else {
		foreach ($model->{$attribute} as $img_url) {
			echo $url ? Html::a(Html::img($thumbnail.ltrim($img_url,"/")), $model->url,['data-pjax'=>0]) : Html::img($thumbnail.ltrim($img_url,"/"));
		}
	}

endif;