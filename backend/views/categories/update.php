<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('backend', 'Создание категории') : $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application', 'app' => $app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Категории'), 'url' => ['categories', 'app' => $app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('/_nav', ['model' => $model]) ?>

    <div class="uk-panel uk-panel-box">
        <h2><?= Yii::t('backend', 'Создание категории') ?></h2>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'layout' => 'stacked', 'field_width' => 'large']); ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'alias')->textInput() ?>

        <?= $form->field($model, 'subtitle')->textInput() ?>

        <?= $form->field($model, 'parent_id')
            ->dropDownList($app->catlist, ['prompt' => Yii::t('backend', 'Корневая категория')]); ?>

        <?= $form->field($model, 'image')->widget(\mihaildev\elfinder\InputFile::className(), [
            'language' => 'ru',
            'controller' => 'elfinder',
            'template' => '<div class="uk-form-row">{input}{button}</div>',
            'options' => ['class' => 'uk-from-controls'],
            'buttonOptions' => ['class' => 'uk-button uk-button-primary'],
            'multiple' => false       // возможность выбора нескольких файлов
        ]); ?>

        <?= $form->field($model, 'preview')->widget(\mihaildev\elfinder\InputFile::className(), [
            'language' => 'ru',
            'controller' => 'elfinder',
            'template' => '<div class="uk-form-row">{input}{button}</div>',
            'options' => ['class' => 'uk-from-controls'],
            'buttonOptions' => ['class' => 'uk-button uk-button-primary'],
            'multiple' => false       // возможность выбора нескольких файлов
        ]); ?>

        <?= $form->field($model, 'state')->checkbox(); ?>

        <hr>

        <?= $form->field($model, 'intro')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
                'preset' => 'standart',
                'allowedContent' => true,
                'height' => '200px',
                'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                'contentsCss' => Yii::$app->zoo->cke_editor_css,
            ]),
        ]); ?>

        <?= $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
                'preset' => 'standart',
                'allowedContent' => true,
                'height' => '200px',
                'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                'contentsCss' => Yii::$app->zoo->cke_editor_css,
            ]),
        ]); ?>

        <?= $form->field($model, 'quote')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
                'preset' => 'standart',
                'allowedContent' => true,
                'height' => '200px',
                'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                'contentsCss' => Yii::$app->zoo->cke_editor_css,
            ]),
        ]); ?>

        <hr>

        <?= $form->field($model, 'lang')->dropDownList(Yii::$app->zoo->languages, ['prompt' => 'язык категории']); ?>

        <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true, 'class' => 'uk-width-1-1']) ?>

        <?= $form->field($model, 'metaDescription')->textarea(['rows' => 2, 'class' => 'uk-width-1-1']) ?>

        <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true, 'class' => 'uk-width-1-1']) ?>

        <div class="uk-form-row uk-margin-large-top">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить'), ['class' => 'uk-button uk-button-success uk-button-large']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php

$url = Url::to(['alias-create']);

$script = <<< JS

$('body')

.on('change','#categories-name',function(){ 
    $.post('$url',{name:$('#categories-name').val()},function(data){ 
        if ($('#categories-alias').val()) { 
            UIkit.modal.confirm('Replace alias? '+data.alias, function(){ 
                $('#categories-alias').val(data.alias) 
            }); 
        } else $('#categories-alias').val(data.alias)
    })
})

JS;

$this->registerJs($script, \yii\web\View::POS_READY);