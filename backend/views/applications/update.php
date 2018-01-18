<?php

use yii\helpers\Html;
use worstinme\zoo\widgets\ActiveForm;

$this->title = Yii::t('zoo', 'APPLICATION_CONTENT_EDIT', ['app' => $model->app_id, 'lang' => $model->lang]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'APPLICATIONS_INDEX_BREADCRUMB'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->app->title, 'url' => ['view', 'app' => $model->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin(['options' => ['class' => 'uk-form-stacked']]); ?>

<div class="uk-grid">
    <div class="uk-width-2-3@m">

        <?= $form->field($model, 'name')->textInput(['class' => 'uk-input']) ?>

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


        <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true, 'class' => 'uk-input']) ?>

        <?= $form->field($model, 'meta_description')->textarea(['rows' => 2, 'class' => 'uk-textarea']) ?>

        <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true, 'class' => 'uk-input']) ?>

        <div class="uk-form-row uk-margin-large-top">
            <?= Html::submitButton(Yii::t('zoo', 'Сохранить'), ['class' => 'uk-button uk-button-success uk-button-large']) ?>
        </div>

    </div>
</div>

<?php ActiveForm::end(); ?>
