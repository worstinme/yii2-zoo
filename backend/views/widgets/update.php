<?php

use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('zoo', 'Созадние пункта меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Настройка меню'), 'url' => ['/'.Yii::$app->controller->module->id.'/menu/index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications items-create">

<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'full']); ?>    

<div class="uk-grid uk-grid-small">
    
    <div class="uk-width-medium-4-5">

        <div class="uk-panel-box">

        	<?= $form->field($model, 'name')->textInput(); ?>

        	<?= $this->render($model->widgetModel->formView,[
        		'model'=>$model->widgetModel,
        	]); ?>

        </div>

	</div>

	<div class="uk-width-medium-1-5">

        <div class="uk-panel-box">

        	<?= $form->field($model, 'type')->textInput(['disabled'=>'disabled']); ?>

        	<?= $form->field($model, 'position'); ?>

            <?= $form->field($model, 'state')->checkbox(); ?>

        </div>

        <div class="uk-margin-top">
		    <?=Html::submitButton('Сохранить',['class'=>'uk-button uk-button-success uk-width-1-1'])?>
		</div>

    </div>

</div>

<?php ActiveForm::end(); ?>

</div>
