<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
	<?php foreach ($model->{$attribute} as $key=>$value): ?>
	<?= Html::activeInput('text', $model, $attribute."[".$key."]",['class'=>'uk-width-1-1']); ?>	
	<?php endforeach ?>
	<?= Html::activeInput('text', $model, $attribute."[]",['class'=>'uk-width-1-1']); ?>	
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
