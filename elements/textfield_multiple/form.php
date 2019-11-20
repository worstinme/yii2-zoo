<?php

use yii\helpers\Html;

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

<div class="uk-from-controls">
	<?php foreach ($model->{$element->attributeName} as $key=>$value): ?>
	<?= Html::activeInput('text', $model, $element->attributeName."[".$key."]",['class'=>'uk-width-1-1']); ?>
	<?php endforeach ?>
	<?= Html::activeInput('text', $model, $element->attributeName."[]",['class'=>'uk-width-1-1']); ?><span class="uk-button"><i class="uk-icon-plus"></i></span>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
