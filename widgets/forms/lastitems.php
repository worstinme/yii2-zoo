<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['id'=>'pjax','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

<?= $form->field($model, 'app_id')->dropDownList($model->applications,['prompt'=>'Выбрать приложение']); ?>

<?php if ($model->app_id): ?>

<?= $form->field($model, 'categories')->dropDownList($model->catlist); ?>	

<?php endif ?>

<?= $form->field($model, 'sort')->dropDownList($model->elements); ?>	

<?php Pjax::end(); ?>

<?= $form->field($model, 'flag')->checkbox(); ?>

<?= $form->field($model, 'desc')->checkbox(); ?>

<?= $form->field($model, 'list_class')->textInput(); ?>

<?php $script = <<<JAVASCRIPT

$.pjax.defaults.scrollTo = false;

$("#pjax").on("change","#lastitems-app_id",function(){
	$(this).parents("form")
		.append($('<input />').attr('type', 'hidden').attr('name', "reload").attr('value', "true"))
		.submit();
});

JAVASCRIPT;

$this->registerJs($script,$this::POS_READY);
