<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('backend','Создание элемента') : $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Элементы'), 'url' => ['elements','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?=$this->render('/_nav',['app'=>$app])?>
<div class="uk-panel uk-panel-box">
<h2><?=$this->title?></h2>

<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'full']); ?>    

	<div class="uk-grid">

		<div class="uk-width-medium-2-3 uk-margin">

			<?= $form->field($model, 'type')->dropdownlist(Yii::$app->controller->module->elements,['prompt'=>' '])?>

			<?= $form->field($model, 'label')->textInput(["data-aliascreate"=>"#elements-name"])  ?>

		    <?= $form->field($model, 'name')->textInput(['placeholder'=>'^[\w_]+'])  ?>

	    </div>

	</div>

    <div class="uk-form-row uk-margin-large-top">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend','Создать') : Yii::t('backend','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>

<?php

$alias_create_url = Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/alias-create']);

$script = <<< JS

(function($){

	$("[data-aliascreate]").on('change',function(){
		var item = $(this),aliastarget = $($(this).data('aliascreate'));
		$.post('$alias_create_url',{name:item.val(),'nodelimiter':true}, function(data) {
				aliastarget.val(data.alias);
				aliastarget.trigger( "change" );
		});
	});

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_END);