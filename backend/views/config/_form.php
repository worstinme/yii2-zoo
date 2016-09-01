<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

    <?php if (Yii::$app->request->get('name') !== null): ?>
    	<?= $form->field($model, 'name')->textInput(['maxlength' => true,'disabled'=>true,'class'=>'uk-disabled']) ?>
    <?php else: ?>
    	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php endif ?>

    <?= $form->field($model, 'category')->textInput(['option' => 'value']); ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6,'class'=>'uk-width-medium-2-3']) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="uk-form-row">
    	<div class="uk-form-controls">
        <?= Html::submitButton('Сохранить', ['class' => 'uk-button uk-button-primary']) ?>
    	</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
