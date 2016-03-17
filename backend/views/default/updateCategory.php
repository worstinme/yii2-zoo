<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('backend','Создание категории') : $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application','app'=>$app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Категории'), 'url' => ['categories','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="applications categories update">
	<div class="uk-grid">
		
		<div class="uk-width-medium-4-5">
		
		<h2><?=Yii::t('backend','Создание категории')?></h2>

		<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large','field_size'=>'large']); ?>

		<?php if ($model->frontendCategory !== null): ?>
			<?= Html::a('Посмотреть на сайте', $model->frontendCategory->url, ['target'=>'_blank','class' => 'uk-margin-top uk-button uk-button-primary uk-float-right']); ?>
		<?php endif ?>
			
		                    
		    <?= $form->field($model, 'name')->textInput(["data-aliascreate"=>"#categories-alias"])  ?>

		    <?= $form->field($model, 'alias')->textInput()  ?>

		    <?= $form->field($model, 'parent_id')
				    	->dropDownList($app->catlist,['prompt'=>Yii::t('backend','Корневая категория')]); ?> 

			<hr>

			<?= $form->field($model, 'preContent')->widget(\mihaildev\ckeditor\CKEditor::className(), [
			        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
			            'preset' => 'standart',
		                'allowedContent' => true,
		                'height'=>'200px',		
                		//'forcePasteAsPlainText'=>true,
		                'contentsCss'=>[
		                	'/assets/58ef8039/css/uikit.min.css',
		                    '/css/site.css',
		                    'https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,400italic,900&amp;subset=latin,cyrillic',
		                ],
			        ]),
			]); ?>

			<?= $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(), [
			        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
			            'preset' => 'standart',
		                'allowedContent' => true,
		                'height'=>'200px',
                		//'forcePasteAsPlainText'=>true,
		                'contentsCss'=>[
		                	'/assets/58ef8039/css/uikit.min.css',
		                    '/css/site.css',
		                    'https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,400italic,900&amp;subset=latin,cyrillic',
		                ],
			        ]),
			]); ?>

			<hr>

			<?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

			<?= $form->field($model, 'metaDescription')->textarea(['rows' => 2,'class'=>'uk-width-1-1']) ?>

			<?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

		    <div class="uk-form-row uk-margin-large-top">
		        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend','Создать') : Yii::t('backend','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
		    </div>

		<?php ActiveForm::end(); ?>

		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('/_nav',['app'=>$app])?>
		</div>
	</div>
</div>

<?php

$alias_create_url = Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/alias-create']);

$script = <<< JS

(function($){

	$("[data-aliascreate]").on('change',function(){
		var item = $(this),aliastarget = $($(this).data('aliascreate'));
		$.post('$alias_create_url',{alias:item.val()}, function(data) {
				aliastarget.val(data);
				aliastarget.trigger( "change" );
		});
	});

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_END);