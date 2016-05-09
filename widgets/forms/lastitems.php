<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$form = ActiveForm::begin(['layout'=>'horizontal','field_width'=>'full']); ?>

<?= $form->field($model, 'app_id')->dropDownList($model->applications,['prompt'=>'Выбрать приложение']); ?>

<?php ActiveForm::end(); ?>