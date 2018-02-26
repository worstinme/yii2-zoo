<?php

use worstinme\zoo\widgets\ActiveForm;
use worstinme\zoo\models\Categories;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('zoo', 'Создание категории') : $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application', 'app' => $app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'Категории'), 'url' => ['categories', 'app' => $app->id]];
$this->params['breadcrumbs'][] = $this->title;

$items = ArrayHelper::map(Categories::find()
    ->where(['app_id' => $model->app_id])
    ->andFilterWhere(['<>', 'id', $model->id])
    ->orderBy('lang, name')
    ->all(), 'id', function ($model) {
    return $model->name . ' / ' . strtoupper($model->lang);
});

?>

    <div class="uk-panel uk-panel-box">
        <h2><?= Yii::t('zoo', 'Создание категории') ?></h2>

        <?php $form = ActiveForm::begin(['options'=>['class'=>'uk-form-horizontal']]); ?>

        <?= $form->field($model, 'name')->textInput(['class'=>'uk-input']) ?>

        <?= $form->field($model, 'alias')->textInput(['class'=>'uk-input']) ?>

        <?= $form->field($model, 'lang')->dropDownList(Yii::$app->zoo->languages, ['class'=>'uk-select']); ?>

        <?= $form->field($model, 'subtitle')->textInput(['class'=>'uk-input']) ?>

        <?= $form->field($model, 'parent_id')
            ->dropDownList($catlist, ['class'=>'uk-select','prompt' => Yii::t('zoo', 'Корневая категория')]); ?>

        <?= $form->field($model, 'image')->widget(\mihaildev\elfinder\InputFile::className(), [
            'language' => 'ru',
            'controller' => 'elfinder',
            'template' => '<div class="uk-margin" uk-margin><div  uk-form-custom="target: true">{input}</div>{button}</div>',
            'options' => ['class' => 'uk-input uk-form-width-medium'],
            'buttonOptions' => ['class' => 'uk-button uk-button-default'],
            'multiple' => false       // возможность выбора нескольких файлов
        ]); ?>

        <?= $form->field($model, 'preview')->widget(\mihaildev\elfinder\InputFile::className(), [
            'language' => 'ru',
            'controller' => 'elfinder',
            'template' => '<div class="uk-margin" uk-margin><div  uk-form-custom="target: true">{input}</div>{button}</div>',
            'options' => ['class' => 'uk-input uk-form-width-medium'],
            'buttonOptions' => ['class' => 'uk-button uk-button-default'],
            'multiple' => false       // возможность выбора нескольких файлов
        ]); ?>

        <?= $form->field($model, 'state')->checkbox(['class'=>'uk-checkbox']); ?>

        <hr>

        <?= $form->field($model, 'intro')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
                'preset' => 'standart',
                'allowedContent' => true,
                'height' => '200px',
            ]),
        ]); ?>

        <?= $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
                'preset' => 'standart',
                'allowedContent' => true,
                'height' => '200px',
            ]),
        ]); ?>

        <?= $form->field($model, 'quote')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
                'preset' => 'standart',
                'allowedContent' => true,
                'height' => '200px',
            ]),
        ]); ?>

        <hr>

        <?= $form->field($model, 'alternateIds')->dropDownList($items,['multiple'=>true,'class'=>'uk-select'])?>

        <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true, 'class' => 'uk-width-1-1']) ?>

        <?= $form->field($model, 'meta_description')->textarea(['rows' => 2, 'class' => 'uk-width-1-1']) ?>

        <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true, 'class' => 'uk-width-1-1']) ?>

        <div class="uk-form-row uk-margin-large-top">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('zoo', 'Создать') : Yii::t('zoo', 'Сохранить'), ['class' => 'uk-button uk-button-success uk-button-large']) ?>
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
            UIkit.modal.confirm('Replace alias? '+data.alias).then(function(){ 
                $('#categories-alias').val(data.alias) 
            }); 
        } else $('#categories-alias').val(data.alias)
    })
})

JS;

$this->registerJs($script, \yii\web\View::POS_READY);