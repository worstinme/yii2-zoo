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
				<li><?=Html::img($img_url);?></li>
			<?php endforeach ?>
	    </ul>
	    <a href=""data-uk-slideset-item="previous"></a>
	    <a href=""data-uk-slideset-item="next"></a>
	</div>

	<?php else: ?>
		<?=Html::img($model->{$attribute}[0]);?>
	<?php endif ?>

<?php else: 

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

endif;