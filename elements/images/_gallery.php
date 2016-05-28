<?php

use \yii\helpers\Html;

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

\worstinme\uikit\assets\Slideset::register($this);

if ($width > 0 && $height > 0) {
	$thumbnail = '/thumbnails/'.$width.'-'.$height.'/';
}

?>

<ul id="slider" class="slider uk-switcher">
<?php foreach ($model->{$attribute} as $key=>$img_url): ?>
	<li class="uk-slidenav-position<?php if ($key == 0): ?> uk-active<?php endif ?>">
		<?=Html::img(!empty($thumbnail) ? $thumbnail.ltrim($img_url,"/") : $img_url);?>
		<a data-uk-switcher-item="previous" class="uk-slidenav uk-slidenav-previous"></a>
		<a data-uk-switcher-item="next" class="uk-slidenav uk-slidenav-next"></a>
	</li>
<?php endforeach ?>
</ul>	

<div  data-uk-slideset="{default: 4}" class="slideset<?php if (count($model->{$attribute}) > 4): ?> uk-slidenav-position<?php endif ?>">
    <ul class="uk-grid uk-grid-small uk-slideset" data-uk-switcher="{connect:'#slider'}">
        <?php foreach ($model->{$attribute} as $img_url): ?>
			<li><?=Html::img('/thumbnails/160-120/'.ltrim($img_url,"/"));?></li>
		<?php endforeach ?>
    </ul>
    <?php if (count($model->{$attribute}) > 4): ?>
    	<a data-uk-switcher-item="previous" class="uk-slidenav uk-slidenav-previous"></a>
		<a data-uk-switcher-item="next" class="uk-slidenav uk-slidenav-next"></a>
    <?php endif ?>
</div>