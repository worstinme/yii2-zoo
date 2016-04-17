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

<?=$this->render('/_nav',['app'=>$app])?>

<h2><?=Yii::t('backend','Создание категории')?></h2>

<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large']); ?>

<?php if ($model->frontendCategory !== null): ?>
	<?= Html::a('Посмотреть на сайте', $model->frontendCategory->url, ['target'=>'_blank','class' => 'uk-margin-top uk-button uk-button-primary uk-float-right']); ?>
<?php endif ?>
	
                    
    <?= $form->field($model, 'name')->textInput(["data-aliascreate"=>"#categories-alias"])  ?>

    <?= $form->field($model, 'alias')->textInput()  ?>

    <?= $form->field($model, 'parent_id')
		    	->dropDownList($app->catlist,['prompt'=>Yii::t('backend','Корневая категория')]); ?> 

	<?= $form->field($model, 'image')->widget(\mihaildev\elfinder\InputFile::className(), [
	    'language'      => 'ru',
	    'controller'    => 'elfinder',     
	    'template'      => '<div class="uk-form-row">{input}{button}</div>',
	    'options'       => ['class' => 'uk-from-controls'],
	    'buttonOptions' => ['class' => 'uk-button uk-button-primary'],
	    'multiple'      => false       // возможность выбора нескольких файлов
	]);?>

	<?= $form->field($model, 'state')->checkbox(); ?>

	<hr>

	<?= $form->field($model, 'intro')->widget(\mihaildev\ckeditor\CKEditor::className(), [
	    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
	        'preset' => 'standart',
            'allowedContent' => true,
            'height'=>'200px',
            'toolbar' => [
			    ['Bold', 'Italic','Underline','-','NumberedList', 'BulletedList', '-', 'Link', 'Unlink',
			    '-','TextColor','BGColor','-','Blockquote','CreateDiv','-','Image','Table','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Outdent','Indent','-','RemoveFormat','Source']
			],
            'contentsCss'=>[
                '/css/site.css',
            ],
	    ]),
	]); ?>

	<?= $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(), [
	    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
	        'preset' => 'standart',
            'allowedContent' => true,
            'height'=>'200px',
            'toolbar' => [
			    ['Bold', 'Italic','Underline','-','NumberedList', 'BulletedList', '-', 'Link', 'Unlink',
			    '-','TextColor','BGColor','-','Blockquote','CreateDiv','-','Image','Table','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Outdent','Indent','-','RemoveFormat','Source']
			],
            'contentsCss'=>[
                '/css/site.css',
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


<?php

$alias_create_url = Url::toRoute(['alias-create']);

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