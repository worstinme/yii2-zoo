<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);


?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<?php foreach ($model->{$attribute} as $key=>$image): ?>
	<div class="uk-from-controls">
		<?= Html::activeInput('text', $model, $attribute."[".$key."]",['class'=>'uk-width-1-1']); ?>
		<div class="uk-form-help-block uk-text-danger"></div>
	</div>	
<?php endforeach ?>

<div class="uk-from-controls">
	<?= Html::activeInput('text', $model, $attribute."[]",['class'=>'uk-width-1-1']); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
