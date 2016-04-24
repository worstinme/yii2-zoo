<?php

use \yii\helpers\Html;

$lightbox = isset($params['lightbox']) && $params['lightbox'] ? true : false;

if ($lightbox ) {
	\worstinme\uikit\assets\Lightbox::register($this);
	$group = uniqid();
}

?>
<?php if (count($model->{$attribute})): ?>
	
		<div class="wall-gallery">

	<?php foreach ($model->{$attribute} as $image): ?>
		<?php if ($lightbox): ?>
			<?=Html::a(Html::img($image,['class'=>'uk-thumbnail']),$image,['data'=>['uk-lightbox'=>'{group:"group-'.$group.'"}']])?> 
		<?php else: ?>
			<?=Html::img($image,['class'=>'uk-thumbnail'])?>
		<?php endif ?>
	<?php endforeach ?>

		</div>
	
<?php endif ?>