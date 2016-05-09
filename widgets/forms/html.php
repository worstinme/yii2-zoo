<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$form = ActiveForm::begin(['layout'=>'horizontal','field_width'=>'full']); ?>

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
	                'toolbar' => [
	                    ['Bold', 'Italic','Underline','-','NumberedList', 'BulletedList', '-', 'Link', 'Unlink',
	                    '-','TextColor','BGColor','-','Blockquote','CreateDiv','-','Image','Table','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Outdent','Indent','-','RemoveFormat','Source','Maximize']
	                ],
	                'contentsCss'=>Yii::$app->cache->get('frontendCss'),
	        ])
	    ])?>
	</div>
</div>

<?php ActiveForm::end(); ?>