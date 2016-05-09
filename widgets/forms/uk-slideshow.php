<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$form = ActiveForm::begin(['layout'=>'horizontal','field_width'=>'full']); ?>

<?= $form->field($model, 'path')->textInput(); ?>

<?php ActiveForm::end(); ?>