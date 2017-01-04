<?php

use \yii\helpers\Html;
use worstinme\zoo\helpers\ImageHelper;

\worstinme\uikit\assets\Slideshow::register($this);
\worstinme\uikit\assets\Slider::register($this);

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;
$sliderHeight = isset($params['sliderHeight']) ? (int)$params['sliderHeight'] : null;

$config = [
	'kenburns'=>false,
	'autoplay'=>true,
];

if ($sliderHeight > 0) {
	$config['height'] = $sliderHeight;
}

if (!empty($params['kenburns']) && $params['kenburns']) {
	$config['kenburns'] = true;
}


$config = \yii\helpers\Json::encode($config);

?>

<div class="uk-slidenav-position" data-uk-slideshow='<?=$config?>' data-uk-check-display>
    <ul class="uk-slideshow">
	<?php foreach ($model->$attribute as $image): ?>
		<li>
			<?php if ($width > 0 && $height > 0) : ?>
				<?=ImageHelper::thumbnailImg('@webroot'.$image, $width, $height, ImageHelper::THUMBNAIL_OUTBOUND, [], 100)?>
			<?php else : ?>
				<?=Html::img($image);?>
			<?php endif ?>
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
					<li data-uk-slideshow-item="<?=$key?>"><?=ImageHelper::thumbnailImg('@webroot'.$image, 160, 120, ImageHelper::THUMBNAIL_OUTBOUND, [], 100)?></li>
				<?php endforeach ?>
	        </ul>
	    </div>
	    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slider-item="previous"></a>
    	<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slider-item="next"></a>
	</div>

	<?php else: ?>

	<ul class="uk-grid uk-grid-width-1-6 uk-grid-width-medium-1-10 uk-grid-small">
	    <?php foreach ($model->$attribute as $key=> $image): ?>
			<li data-uk-slideshow-item="<?=$key?>"><?=ImageHelper::thumbnailImg('@webroot'.$image, 160, 120, ImageHelper::THUMBNAIL_OUTBOUND, [], 100)?>
		<?php endforeach ?>
	</ul>

	<?php endif ?>
</div>