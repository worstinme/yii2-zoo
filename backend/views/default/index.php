<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Html;

use worstinme\zoo\backend\models\Items;

$this->title = Yii::t('backend','Приложения');

?>

<div class="applications">

<?php if (count($applications)): ?>

	<div class="uk-grid">

		<?php foreach ($applications as $app): ?>

			<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-4 uk-grid-margin">
				<?=Html::a('<i class="uk-icon-cog"></i> '.$app->title,$app->url,
									['class'=>'uk-panel uk-border-rounded uk-panel-box uk-text-center'])?>
			</div>
		
		<?php endforeach ?>	

		<div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-4 uk-grid-margin">
			<?=Html::a('<i class="uk-icon-plus"></i> '.Yii::t('backend','Создать приложение'),
					['/'.Yii::$app->controller->module->id.'/default/update'],
					['class'=>'uk-panel uk-panel-box uk-text-center uk-border-rounded'])?>
		</div>
		
	</div>

	<hr class="uk-margin-large-top">

<?php else: ?>

	<p><?=Yii::t('backend','У вас еще нет ни одного приложения')?></p>

	<hr class="uk-margin-large-top">

	<h2><?=Yii::t('backend','Создать приложение')?></h2>


	<?php $form = ActiveForm::begin(['action'=>['/'.Yii::$app->controller->module->id.'/default/update'],'id' => 'login-form','layout'=>'stacked','field_width'=>'large','field_size'=>'large']); ?>
	                    
	    <?= $form->field($model, 'title')->textInput()  ?>

	    <?= $form->field($model, 'name')->textInput()  ?>
	                 
	    <div class="uk-form-row">
	        <?= Html::submitButton(Yii::t('backend','Создать'),['class'=>'uk-button uk-button-primary uk-button-large']) ?>
	    </div>

	<?php ActiveForm::end(); ?>

<?php endif ?>

</div>