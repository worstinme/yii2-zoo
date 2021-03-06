<?php

use yii\helpers\Html;
use mihaildev\elfinder\InputFile;



$input_id = Html::getInputId($model,$element->attributeName);

?>

<?php if (!empty($element->admin_hint)): ?>
	<i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?=$input_id?>'}"></i>
	<?= Html::activeLabel($model, $element->attributeName,['class'=>'uk-form-label']); ?>
	<p class="hint-<?=$input_id?> uk-hidden">
	    <?=$element->admin_hint?>
	</p>
<?php else: ?>
	<?= Html::activeLabel($model, $element->attributeName,['class'=>'uk-form-label']); ?>
<?php endif ?>

<?php foreach ($model->{$element->attributeName} as $key=>$image): ?>
	<div class="uk-from-controls">
		<?= InputFile::widget([
		    'language'   => 'ru',
		    'controller' => 'elfinder',
		    'model'       => $model,
		    'attribute'      => $element->attributeName."[".$key."]",
		    'buttonName'=>'Выбрать',
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]); ?>
		<div class="uk-form-help-block uk-text-danger"></div>
	</div>
<?php endforeach ?>

<div class="uk-from-controls">
	<?= InputFile::widget([
		    'language'   => 'ru',
		    'controller' => 'elfinder',
		    'model'       => $model,
		    'attribute'      => $element->attributeName."[]",
		    'buttonName'=>'Выбрать',
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
