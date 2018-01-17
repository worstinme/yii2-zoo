<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;
use worstinme\zoo\models\Items;

$this->title = Yii::t('zoo','Настройки приложения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

$sort_attributes = [];

foreach ((new Items)->attributes() as $attribute) {
	$sort_attributes[$attribute] = $attribute;
}

?>

<?=$this->render('/_nav',['app'=>$model])?>

<div class="uk-panel uk-panel-box">
<h2>Настройка приложения: <em><?=$model->name?></em></h2>

<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large']); ?>

<div class="uk-grid">

<div class="uk-width-medium-2-3">
	<?= $form->field($model, 'title')->textInput()  ?>

	<?= $form->field($model, 'intro')->widget(\mihaildev\ckeditor\CKEditor::className(), [
	        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
	            'preset' => 'standart',
                'allowedContent' => true,
                'height'=>'200px',
                
                'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                'contentsCss'=>Yii::$app->zoo->cke_editor_css,
	        ]),
	]); ?>

    <?= $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(), [
	        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
	            'preset' => 'standart',
                'allowedContent' => true,
                'height'=>'200px',
                
                'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                'contentsCss'=>Yii::$app->zoo->cke_editor_css,
	        ]),
	]); ?>

	
	<?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

	<?= $form->field($model, 'metaDescription')->textarea(['rows' => 2,'class'=>'uk-width-1-1']) ?>

	<?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

	<div class="uk-form-row uk-margin-large-top">
	    <?= Html::submitButton(Yii::t('zoo','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
	</div> 

	<div class="uk-form-row uk-margin-large-top uk-margin-large-bottom">

	<?=Html::a('<i class="uk-icon-trash"></i> Удалить приложение', ['/'.Yii::$app->controller->module->id.'/default/delete','app'=>$model->id], [
        'title' => Yii::t('zoo', 'Удалить'),
        'target'=>'_blank',
        'data'=>['method'=>'post','confirm'=>'Вы точно хотите удалить приложение?',],
    ]); ?>

	</div>

</div>

<div class="uk-width-medium-1-3">

	<?= $form->field($model, 'simpleItemLinks')->checkbox(); ?>

	<?= $form->field($model, 'filters')->checkbox(); ?>

	<?= $form->field($model, 'itemsSearch')->checkbox(); ?>

	<?= $form->field($model, 'itemsSort')->checkbox(); ?>

	<?= $form->field($model, 'defaultOrder')->dropDownList($sort_attributes); ?>

	<?= $form->field($model, 'defaultOrderDesc')->checkbox(); ?>

	<?= $form->field($model, 'itemsColumns')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

	<?= $form->field($model, 'defaultPageSize')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

</div>

</div>

<?php ActiveForm::end(); ?>
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