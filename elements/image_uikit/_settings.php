<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<?= $form->field($model,'maxFileSize')->textInput(['type'=>'number']); ?>
<?= $form->field($model,'dir')->textInput(); ?>
<?= $form->field($model,'temp')->textInput(); ?>
<?= $form->field($model,'horizontalResizeWidth')->textInput(); ?>
<?= $form->field($model,'verticalResizeWidth')->textInput(); ?>
<?= $form->field($model,'rename')->checkbox([]); ?>
<?= $form->field($model,'spread')->checkbox([]); ?>

