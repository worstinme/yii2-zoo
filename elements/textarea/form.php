<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model, $element->attributeName);

if ($element->editor) {

    echo \mihaildev\ckeditor\CKEditor::widget([
        'model' => $model,
        'attribute' => $element->attributeName,
        'options' => ['value'=>$model->prepareTextareaValue($model->{$element->attributeName})],
        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'], [
            'preset' => 'standart',
            'allowedContent' => true,
            'height' => '200px',
            'toolbar' => [
                ['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', 'Styles', 'Font', 'FontSize', 'Format', 'TextColor', 'BGColor', '-', 'Blockquote', 'CreateDiv', '-', 'Image', 'Table', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Outdent', 'Indent', '-', 'RemoveFormat', 'Source', 'Maximize']
            ],
            // 'contentsCss' => Yii::$app->zoo->cke_editor_css,
        ]),
    ]);

} else {
    echo Html::activeTextarea($model, $element->attributeName, ['rows' => 3, 'class' => 'uk-textarea']);
}
