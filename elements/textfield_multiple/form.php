<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);

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
	<?php foreach ($model->{$attribute} as $key=>$value): ?>
	<?= Html::activeInput('text', $model, $attribute."[".$key."]",['class'=>'uk-width-1-1']); ?>	
	<?php endforeach ?>
	<?= Html::activeInput('text', $model, $attribute."[]",['class'=>'uk-width-1-1']); ?><span class="uk-button"><i class="uk-icon-plus"></i></span>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
