<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<div class="uk-form-row">
	<div class="uk-form-controls">	
		<?php if (is_array($model->variants) && count($model->variants)): ?>
			<?php foreach ($model->variants as $key=>$value): ?>
				<div class="ro"><?= Html::activeTextInput($model, 'variants['.$key.']', ['value'=>$value,'class' => 'uk-width-1-2']); ?> <a href="#" data-remove-row><i class="uk-icon-trash"></i></a></div>
			<?php endforeach ?>
		<?php endif ?>

		<a href="#" class="dfn" data-add-row="<?= count($model->variants) ?>">Добавить вариант</a>

	</div>
</div>

<?php

$script = <<<JS

	$("[data-add-row]").on("click",function(e){
		e.preventDefault();
		var index = Number($(this).data('add-row'));
		$(this).before('<div><input type="text" name="Elements[variants]['+index+']" class="uk-width-1-2"> <a href="#" data-remove-row"><i class="uk-icon-trash"></i></a></div>');
		console.log("<input type='text' name='Elements[variants]["+index+"]>");
		$(this).data('add-row',index+1);
	});

	$("body").on("click","[data-remove-row]",function(e){
		$(this).parent(".ro").remove();
		e.preventDefault();
	});

JS;

$this->registerJs($script,$this::POS_READY);