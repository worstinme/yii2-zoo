<?php

use \yii\helpers\Html;

\worstinme\uikit\assets\Slideshow::register($this);
\worstinme\uikit\assets\Slider::register($this);

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

if ($width > 0 && $height > 0) {
	$thumbnail = '/thumbnails/'.$width.'-'.$height.'/';
}

$config = [
	'kenburns'=>true,
	'autoplay'=>true,
];

if (empty($thumbnail) && $height > 0) {
	$config['height'] = $height;
}


$config = \yii\helpers\Json::encode($config);

?>

<div class="uk-slidenav-position" data-uk-slideshow='<?=$config?>' data-uk-check-display>
    <ul class="uk-slideshow">
	<?php foreach ($model->$attribute as $image): ?>
		<li>
			<?=Html::img(!empty($thumbnail) ? $thumbnail.ltrim($image,"/") : $image);?>
		</li>
	<?php endforeach ?>
    </ul>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>

    <?php if (count($model->$attribute) > 10): ?>
    
   	<div class="uk-slidenav-position uk-margin-top" data-uk-slider="{}">
	    <div class="uk-slider-container">
	         <ul class="uk-slider uk-grid uk-hidden-small uk-grid-width-1-5 uk-grid-width-medium-1-10 uk-grid-small">
	            <?php foreach ($model->$attribute as $key=> $image): ?>
					<li data-uk-slideshow-item="<?=$key?>"><?=Html::img('/thumbnails/160-120/'.ltrim($image,"/"));?></li>
				<?php endforeach ?>
	        </ul>
	    </div>
	    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slider-item="previous"></a>
    	<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slider-item="next"></a>
	</div>

	<?php else: ?>

	<ul class="uk-grid uk-grid-width-1-6 uk-grid-width-medium-1-10 uk-grid-small">
	    <?php foreach ($model->$attribute as $key=> $image): ?>
			<li data-uk-slideshow-item="<?=$key?>"><?=Html::img('/thumbnails/160-120/'.ltrim($image,"/"));?>
		<?php endforeach ?>
	</ul>

	<?php endif ?>
</div>