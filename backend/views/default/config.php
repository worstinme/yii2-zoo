<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;
use worstinme\zoo\models\Item;

$this->title = Yii::t('backend','Настройки приложения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications applications-index">
	<div class="uk-grid">
		<div class="uk-width-medium-4-5">

		<h2>Типы материалов</h2>

		<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large']); ?>

			<?= $form->field($app, 'title')->textInput()  ?>
			
			<?= $form->field($app, 'name')->textInput()  ?>	

			<?= $form->field($app, 'viewPath',['template' => '{label}<div class="uk-form-controls"><div class="uk-button uk-button-primary"><i class="uk-icon-at"></i> app</div>{input}</div>'])->textInput()  ?>	

		    <?= $form->field($app, 'frontpage')->widget(\mihaildev\ckeditor\CKEditor::className(), [
			        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
			            'preset' => 'standart',
		                'allowedContent' => true,
		                'height'=>'200px',
		                'contentsCss'=>[
		                    '/css/site.css',
		                ],
			        ]),
			]); ?>

			<?= $form->field($app, 'catlinks')->checkbox() ?>

			<hr>

			<?= $form->field($app, 'metaTitle')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

			<?= $form->field($app, 'metaDescription')->textarea(['rows' => 2,'class'=>'uk-width-1-1']) ?>

			<?= $form->field($app, 'metaKeywords')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>



		<?php /*	<div class="uk-form-row">
				<label class="uk-form-label"><?=$app->getAttributeLabel('types')?></label>
				<?php if (count($app->types)): ?>
				<?php foreach ($app->types as $key => $value): ?>
				<div class="uk-form-controls uk-margin-small-top">	
					<?=Html::TextInput('Applications[types][]',$value,['class'=>'uk-form-width-large','size'=>'40'])?>
					<a class="uk-form-help-inline delete" data-delete-row=""><i class="uk-icon-trash"></i></a>
				</div>
				<?php endforeach ?>				
				<?php endif ?>
				<div class="uk-form-controls uk-margin-small-top">	
					<?=Html::TextInput('Applications[types][]','',['class'=>'uk-form-width-large','size'=>'40'])?>
					<a class="uk-form-help-inline add" data-add-row><i class="uk-icon-plus"></i></a>
				</div>
			</div> */ ?>

			<div class="uk-form-row uk-margin-large-top">
			    <?= Html::submitButton(Yii::t('backend','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
			</div>  
 
		<?php ActiveForm::end(); ?>
			
		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('/_nav',['app'=>$app])?>
		</div>
	</div>
</div>

<?php

$script = <<< JS

(function($){

$("body")
		.on("click","[data-delete-row]",function(e){
			e.preventDefault();
			if(confirm('Delete row?'))	{
				if ($(this).next().hasClass('add')) {
					$(this).parent('.uk-form-controls').prev().append($(this).next());
				}
				$(this).parent('.uk-form-controls').remove();
			}
		})
		.on("click","[data-add-row]",function(){
			var container = $(this).parent('.uk-form-controls'),
			item = container.clone(true);
			$(this).remove();
			item.find('input:first-child').val('');
			if (!item.find('input:first-child').next().hasClass('delete')) {
				item.find('input:first-child').after('<a class="uk-form-help-inline delete" data-delete-row><i class="uk-icon-trash"></i></a>');
			}
			container.after(item);
		});

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_END);