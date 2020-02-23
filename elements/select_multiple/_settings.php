<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<?= $form->field($model, 'variantsType')->dropDownList($model->variantsTypes); ?>

<div class="uk-form-row">
	<?= Html::activeLabel($model, 'variantsParams', ['class'=>'uk-form-label']); ?>
	<div class="uk-form-controls">
		<?= Html::activeTextInput($model, 'variantsParams', ['class' => 'uk-width-1-2']); ?>
	</div>
</div>

<div class="uk-form-row">
	<?= Html::activeLabel($model, 'variant', ['class'=>'uk-form-label']); ?>
	<div class="uk-form-controls">
		<?php if (is_array($model->variant) && count($model->variant)): ?>
			<?php foreach ($model->variant as $key=>$value): ?>
				<div class="ro"><?= Html::activeTextInput($model, 'variant['.$key.']', ['value'=>$value,'class' => 'uk-width-1-2']); ?> <a href="#" data-remove-row><i class="uk-icon-trash"></i></a></div>
			<?php endforeach ?>
		<?php endif ?>

		<a href="#" class="dfn" data-add-row="<?= count($model->variant) ?>">Добавить вариант</a>

	</div>
</div>

<?php

$script = <<<JS

	$("[data-add-row]").on("click",function(e){
		e.preventDefault();
		var index = Number($(this).data('add-row')) + 1;
		$(this).before('<div><input type="text" name="Element[variant]['+index+']" class="uk-width-1-2"> <a href="#" data-remove-row"><i class="uk-icon-trash"></i></a></div>');
		console.log("<input type='text' name='Element[variant]["+index+"]>");
		$(this).data('add-row',index);
	});

	$("body").on("click","[data-remove-row]",function(e){
		$(this).parent(".ro").remove();
		e.preventDefault();
	});

JS;

$this->registerJs($script,$this::POS_READY);
