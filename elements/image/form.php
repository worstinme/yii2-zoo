<?php

use yii\helpers\Html;
use mihaildev\elfinder\InputFile;

?>

<?= InputFile::widget([
    'language' => 'ru',
    'controller' => 'elfinder',
    'model' => $model,
    'attribute' => $element->attributeName,
    'template' => '<div class="uk-margin" uk-margin><div  uk-form-custom="target: true">{input}</div>{button}</div>',
    'options' => ['class' => 'uk-input uk-form-width-medium'],
    'buttonName' => 'Выбрать',
    'buttonOptions' => ['class' => 'uk-button uk-button-primary'],
]); ?>
<div class="uk-form-help-block uk-text-danger"></div>