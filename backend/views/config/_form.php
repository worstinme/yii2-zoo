<?php

use yii\helpers\Html;
use worstinme\zoo\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model worstinme\zoo\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(['options' => ['class'=>'uk-form-horizontal']]); ?>

    <?php if (Yii::$app->request->get('name') !== null): ?>
    	<?= $form->field($model, 'name')->textInput(['maxlength' => true,'disabled'=>true,'class'=>'uk-input uk-disabled']) ?>
    <?php else: ?>
    	<?= $form->field($model, 'name')->textInput(['class'=>'uk-input','maxlength' => true]) ?>
    <?php endif ?>

    <?= $form->field($model, 'category')->textInput(['class'=>'uk-input']); ?>

    <?= $form->field($model, 'value')->textarea(['class'=>'uk-textarea','rows' => 4]) ?>

    <?= $form->field($model, 'comment')->textInput(['class'=>'uk-input','maxlength' => true]) ?>

    <div class="uk-form-row">
    	<div class="uk-form-controls">
        <?= Html::submitButton('Сохранить', ['class' => 'uk-button uk-button-primary']) ?>
    	</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
