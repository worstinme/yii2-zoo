<?php

use yii\helpers\Html;

?>

<?= $form->field($model, 'checkboxLabel')->textInput(['class'=>'uk-input']) ?>

<?= $form->field($model, 'multiple')->checkbox(['class'=>'uk-checkbox']) ?>

<div class="uk-form-row">
    <?= Html::activeLabel($model, 'variant', ['class'=>'uk-form-label']); ?>
    <div class="uk-form-controls">
        <?php if (is_array($model->variant) && count($model->variant)): ?>
            <?php foreach ($model->variant as $key => $value): ?>
                <div class="row uk-margin">
                    <?= Html::activeTextInput($model, "variant[$key][key]", ['value'=>$value['key'],'class' => 'uk-input uk-width-1-5 uk-margin-right']); ?>
                    <?= Html::activeTextInput($model, "variant[$key][value]", ['value'=>$value['value'],'class' => 'uk-input uk-width-3-5']); ?>
                    <a href="#" data-remove-row><i class="uk-icon-trash"></i></a></div>
            <?php endforeach ?>
        <?php endif ?>

        <a href="#" class="dfn" data-add-row="<?= is_array($model->variant) ? count($model->variant) : 0?>">Добавить вариант</a>

    </div>
</div>



<?php

$script = <<<JS

	$("[data-add-row]").on("click",function(e){
		e.preventDefault();
		var index = Number($(this).data('add-row'));
		$(this).before('<div class="row uk-margin"><input type="text" name="Element[variant]['+index+'][key]" value="'+index+'" class="uk-input uk-width-1-5 uk-margin-right"><input type="text" name="Element[variant]['+index+'][value]" class="uk-input uk-width-3-5"> <a href="#" data-remove-row><i uk-icon="icon: trash"></i></a></div>');
		$(this).data('add-row',index+1);
	});

	$("body").on("click","[data-remove-row]",function(e){
		$(this).parent(".row").remove();
		e.preventDefault();
	});

JS;

$this->registerJs($script,$this::POS_READY);