<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('admin','Создание элемента') : $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application','app'=>$app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Элементы'), 'url' => ['fields','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="applications categories update">
	<div class="uk-grid">
		
		<div class="uk-width-medium-4-5">
		
		<h2><?=$this->title?>: <em><?=$model->fieldname?></em></h2>

		<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'full']); ?>    

			<div class="uk-grid">

				<div class="uk-width-medium-2-3 uk-margin">

					<?= $form->field($model, 'title')->textInput(["data-aliascreate"=>"#".$model->alias."-name"])  ?>

				    <?= $form->field($model, 'name')->textInput()  ?>

			    </div>

			    <div class="uk-width-medium-1-3 uk-margin">

				    <?= $form->field($model, 'required')->checkbox() ?>

					<?= $form->field($model, 'filter')->checkbox() ?>

			    </div>

			    <div class="uk-width-1-1 uk-margin">
					
					<?=$this->render($model->formview,[
						'model'=>$model,
						'form'=>$form,
					])?>

				</div>
				<div class="uk-width-medium-2-3 uk-margin">

					<legend>Зависимости:</legend>

					<div class="uk-form-row uk-form-controls-text">Укажите при каких параметрах, данное поле будет появляться в формах и фильтрах.</div>

					<?= $form->field($model, 'categories')->dropdownlist(array_merge([0=>Yii::t('admin','Все категории')],$app->catlist),['multiple' => 'multiple','size'=>(count($app->catlist) > 9 ? 10 : count($app->catlist)+1)]) ?>

					<?= $form->field($model, 'categories')->dropdownlist($app->typelist,['multiple' => 'multiple','size'=>(count($app->typelist) > 10 ? 10 : count($app->typelist))]) ?>

					<?= $form->field($model, 'placeholder')->textInput()  ?>
				</div>

				<div class="uk-width-medium-1-3 uk-margin">

				</div>
				

			</div>

		    <div class="uk-form-row uk-margin-large-top">
		        <?= Html::submitButton($model->isNewRecord ? Yii::t('admin','Создать') : Yii::t('admin','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
		    </div>

		<?php ActiveForm::end(); ?>

		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('_nav',['app'=>$app])?>
		</div>
	</div>
</div>

<?php

$alias_create_url = Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/alias-create']);

$script = <<< JS

(function($){

	$("[data-aliascreate]").on('change',function(){
		var item = $(this),aliastarget = $($(this).data('aliascreate'));
		$.post('$alias_create_url',{alias:item.val(),'nodelimiter':'true'}, function(data) {
				aliastarget.val(data);
				aliastarget.trigger( "change" );
		});
	});

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_END);