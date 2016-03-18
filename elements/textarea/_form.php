<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);  ?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
    
    <?=\mihaildev\ckeditor\CKEditor::widget([
        'model'=>$model,
        'attribute'=>$attribute,
        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
                'preset' => 'standart',
                'allowedContent' => true,
                'height'=>'400px',
                'contentsCss'=>[
                    '/css/site.css',
                    'https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,400italic,900&subset=latin,cyrillic',
                ],
        ])
    ])?>

    <div class="uk-form-help-block uk-text-danger"></div>

</div>