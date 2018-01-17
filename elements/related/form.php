<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model,$attribute);

?>

<?php if (!empty($element->admin_hint)): ?>
	<i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?=$input_id?>'}"></i>
	<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>
	<p class="hint-<?=$input_id?> uk-hidden">
		<?=$element->admin_hint?>
	</p>
<?php else: ?>
	<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>
<?php endif ?>


<div class="uk-from-controls">
	<?= Html::activeDropDownList($model, $attribute, $element->items, ['prompt' => 'select']); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
