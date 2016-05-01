<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5); 

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
    
    <?=\mihaildev\ckeditor\CKEditor::widget([
        'model'=>$model,
        'attribute'=>$attribute,
        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
                'preset' => 'standart',
                'allowedContent' => true,
                'height'=>'200px',
                'toolbar' => [
                    ['Bold', 'Italic','Underline','-','NumberedList', 'BulletedList', '-', 'Link', 'Unlink',
                    '-','TextColor','BGColor','-','Blockquote','CreateDiv','-','Image','Table','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Outdent','Indent','-','RemoveFormat','Source','Maximize']
                ],
                'contentsCss'=>Yii::$app->cache->get('frontendCss-'.$model->app->name),
        ])
    ])?>

    <div class="uk-form-help-block uk-text-danger"></div>

</div>
