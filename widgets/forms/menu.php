<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$form = ActiveForm::begin(['layout'=>'horizontal','field_width'=>'full']); ?>

<?= $form->field($model, 'name')->dropDownList($model->menuList,['prompt'=>'Выбрать меню']); ?>

<?= $form->field($model, 'class')->textInput(); ?>

<?= $form->field($model, 'navbar')->checkbox(); ?>

<?php ActiveForm::end(); ?>