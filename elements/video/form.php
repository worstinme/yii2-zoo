<?php

use yii\helpers\Html;
use mihaildev\elfinder\InputFile;

$this->registerJs($model->addValidators($this,$attribute), 5);

$input_id = Html::getInputId($model,$attribute);

?>

<?php if (!empty($element->adminHint)): ?>
	<i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?=$input_id?>'}"></i>
	<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?> 
	<p class="hint-<?=$input_id?> uk-hidden">
	    <?=$element->adminHint?>
	</p>	
<?php else: ?>
	<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?> 
<?php endif ?>

<div class="uk-from-controls">
	<?= Html::activeInput('text', $model, $attribute,['class'=>'uk-width-1-1']); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>