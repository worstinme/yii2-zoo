<?php

use yii\helpers\Html;


echo Html::activeTextarea($model, $element->attributeName, ['rows' => 3, 'class' => 'uk-textarea']);

if ($element->editor) {
    $input_id = Html::getInputId($model, $element->attributeName);
    \worstinme\zoo\backend\assets\CKEditor4Asset::register($this);

    $lang = Yii::$app->language;

    $script = <<<JS
    
CKEDITOR.replace( '$input_id', {
        language: '$lang',
    });
    
JS;

    $this->registerJs($script, $this::POS_READY);
}