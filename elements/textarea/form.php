<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model, $element->attributeName);

if ($element->editor) {

    echo \mihaildev\ckeditor\CKEditor::widget([
        'model' => $model,
        'attribute' => $element->attributeName,
        'editorOptions' => [
            'preset' => 'standart', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'inline' => false, //по умолчанию false
            'toolbar' => [
                ['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', 'Styles', 'Font', 'FontSize', 'Format', 'TextColor', 'BGColor', '-', 'Blockquote', 'CreateDiv', '-', 'Image', 'Table', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Outdent', 'Indent', '-', 'RemoveFormat', 'Source', 'Maximize']
            ],
        ],
    ]);

} else {
    echo Html::activeTextarea($model, $element->attributeName, ['rows' => 3, 'class' => 'uk-textarea']);
}
