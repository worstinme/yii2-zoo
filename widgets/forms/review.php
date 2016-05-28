<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$form = ActiveForm::begin(['layout'=>'horizontal','field_width'=>'full']); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => 255]); ?>

<?= $form->field($model, 'subtitle')->textInput(['maxlength' => 255]); ?>

<div class="uk-form-row">
	<?= Html::activeLabel($model, 'image',['class'=>'uk-form-label']); ?>
	<div class="uk-from-controls">
		<?= \mihaildev\elfinder\InputFile::widget([
			    'language'   => 'ru',
			    'controller' => 'elfinder', 
			    'model'       => $model,
			    'attribute'      => 'image',
			    'buttonName'=>'Выбрать',
			    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
			]); ?>
		<div class="uk-form-help-block uk-text-danger"></div>
	</div>
</div>

<div class="uk-form-row">
	<?= Html::activeLabel($model, 'content', ['class' => 'uk-form-label']); ?>
	<div class="uk-form-controls">
	<?=\mihaildev\ckeditor\CKEditor::widget([
	        'model'=>$model,
	        'attribute'=>'content',
	        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
	                'preset' => 'standart',
	                'allowedContent' => true,
	                'height'=>'200px',	                
                	'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                	'contentsCss'=>Yii::$app->zoo->cke_editor_css,
	        ])
	    ])?>
	</div>
</div>

<?php ActiveForm::end(); ?>