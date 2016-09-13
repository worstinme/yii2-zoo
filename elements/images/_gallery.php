<?php

use \yii\helpers\Html;
use worstinme\zoo\helpers\ImageHelper;

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

\worstinme\uikit\assets\Slideset::register($this);

?>

<ul id="slider" class="slider uk-switcher">
<?php foreach ($model->{$attribute} as $key=>$img_url): ?>
	<li class="uk-slidenav-position<?php if ($key == 0): ?> uk-active<?php endif ?>">
		<?=Html::img(!empty($thumbnail) ? $thumbnail.ltrim($img_url,"/") : $img_url);?>
		<?php if ($width > 0 && $height > 0) : ?>
			<?=ImageHelper::thumbnailImg('@webroot'.$img_url, $width, $height, ImageHelper::THUMBNAIL_OUTBOUND, [], 100)?>
		<?php else : ?>
			<?=Html::img($img_url);?>
		<?php endif ?>
		<a data-uk-switcher-item="previous" class="uk-slidenav uk-slidenav-previous"></a>
		<a data-uk-switcher-item="next" class="uk-slidenav uk-slidenav-next"></a>
	</li>
<?php endforeach ?>
</ul>	

<div  data-uk-slideset="{default: 4}" class="slideset<?php if (count($model->{$attribute}) > 4): ?> uk-slidenav-position<?php endif ?>">
    <ul class="uk-grid uk-grid-small uk-slideset" data-uk-switcher="{connect:'#slider'}">
        <?php foreach ($model->{$attribute} as $img_url): ?>
			<li><?=ImageHelper::thumbnailImg('@webroot'.$img_url, 160, 120, ImageHelper::THUMBNAIL_OUTBOUND, [], 100)?></li>
		<?php endforeach ?>
    </ul>
    <?php if (count($model->{$attribute}) > 4): ?>
    	<a data-uk-switcher-item="previous" class="uk-slidenav uk-slidenav-previous"></a>
		<a data-uk-switcher-item="next" class="uk-slidenav uk-slidenav-next"></a>
    <?php endif ?>
</div>