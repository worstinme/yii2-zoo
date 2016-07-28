<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('backend','Приложения');

?>

<div class="applications">

<?php if (count($applications)): ?>

	<div class="uk-grid">

		<?php foreach ($applications as $app): ?>

			<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-4 uk-grid-margin">
				<?=Html::a('<i class="uk-icon-cog"></i> '.$app->title,['/'.Yii::$app->controller->module->id.'/items/index','app'=>$app->id],
									['class'=>'uk-panel uk-border-rounded uk-panel-box uk-text-center'])?>
			</div>
		
		<?php endforeach ?>		

		<?php if ($this->context->module->accessRoles !== null || Yii::$app->user->can('admin')) : ?>
	
		<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-4 uk-grid-margin">
			<?=Html::a('<i class="uk-icon-plus"></i> '.Yii::t('backend','Создать приложение'),
					['/'.Yii::$app->controller->module->id.'/default/create'],
					['class'=>'uk-panel uk-panel-box uk-text-center uk-border-rounded'])?>
		</div>

		<?php endif ?>
		
	</div>

	<hr class="uk-margin-large-top">

<?php elseif($this->context->module->accessRoles !== null || Yii::$app->user->can('admin')): ?>

	<p><?=Yii::t('backend','У вас еще нет ни одного приложения')?></p>

	<?php if (Yii::$app->user->can('superadmin')): ?>

	<hr class="uk-margin-large-top">

	<h2><?=Yii::t('backend','Создать приложение')?></h2>

	<?php $form = ActiveForm::begin(['action'=>['/'.Yii::$app->controller->module->id.'/default/create'],'id' => 'login-form','layout'=>'stacked','field_width'=>'large','field_size'=>'large']); ?>
	                    
	    <?= $form->field($model, 'title')->textInput()  ?>

	    <?= $form->field($model, 'name')->textInput()  ?>

	    <?= $form->field($model, 'app_alias')->textInput(['placeholder'=>'@app'])  ?>

	    <?= $form->field($model, 'model_table_name')->textInput()  ?>

	    <?= $form->field($model, 'example')->dropDownList($model->examples)  ?>

	    <div class="uk-form-row">
	        <?= Html::submitButton(Yii::t('backend','Создать'),['class'=>'uk-button uk-button-primary uk-button-large']) ?>
	    </div>

	<?php ActiveForm::end(); ?>
		
	<?php endif ?>

<?php endif ?>

</div>