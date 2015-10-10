<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('admin','Настройки приложения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications applications-index">
	<div class="uk-grid">
		<div class="uk-width-medium-4-5">

		
		<h2>Типы материалов</h2>

		<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large']); ?>

			<?= $form->field($app, 'title')->textInput()  ?>
			
			<?= $form->field($app, 'name')->textInput()  ?>				

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
			</div>

			<div class="uk-form-row uk-margin-large-top">
			    <?= Html::submitButton(Yii::t('admin','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
			</div>  */ ?>
 
		<?php ActiveForm::end(); ?>
			
		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('_nav',['app'=>$app])?>
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