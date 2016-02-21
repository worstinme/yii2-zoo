<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);

Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
    <?=\dosamigos\ckeditor\CKEditor::widget([
        'model'=>$model,
        'attribute'=> $attribute,
        'options' => ['rows' => '9'],
        'preset' => 'basic'
    ])?>
    <div class="uk-form-help-block uk-text-danger"></div>
</div>

<?//= Html::activeTextarea($field, $field->name, ['option' => $field->value]); ?>	

<?/*= \vova07\imperavi\Widget::widget([
    'selector' => '#field-opisanie',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
]); */