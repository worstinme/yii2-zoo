<?php

use yii\helpers\Html; 
use yii\imagine\Image;
use Imagine\Image\Box;

\worstinme\uikit\assets\Slideset::register($this);

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

if ($width > 0 && $height > 0) {
	$thumbnail = '/thumbnails/'.$width.'-'.$height.'/';
}

if (isset($params['wall']) && $params['wall']) {
	echo $this->render('_wall',['params'=>$params,'model'=>$model,'attribute'=>$attribute]);
}
else {

?>

<?php if (isset($params['gallery']) && $params['gallery'] == 1) : ?>

	<?php if (count($model->{$attribute}) > 1): ?>

	<ul id="slider" class="slider uk-switcher">
		<?php foreach ($model->{$attribute} as $key=>$img_url): ?>
				<li class="uk-slidenav-position<?php if ($key == 0): ?> uk-active<?php endif ?>">
					<a data-uk-switcher-item="previous" class="uk-slidenav uk-slidenav-previous"></a>
					<a data-uk-switcher-item="next" class="uk-slidenav uk-slidenav-next"></a>
			<?php if ($width > 0 && $height > 0): ?>
					<?=Html::img($thumbnail.ltrim($img_url,"/"));?>
			<?php else: ?>
					<?=Html::img($img_url);?>
			<?php endif ?>
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

	<?php elseif(isset($model->{$attribute}[0])):  ?>
		<div class="img"><?=!empty($thumbnail)?Html::img($thumbnail.ltrim($model->{$attribute}[0],"/")):Html::img($model->{$attribute}[0]);?></div>
	<?php endif ?>

<?php elseif(isset($model->{$attribute}[0])): 

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

endif;

}