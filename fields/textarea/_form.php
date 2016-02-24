<?php

use yii\helpers\Html;
use mihaildev\elfinder\ElFinder;
use mihaildev\ckeditor\CKEditor;

$this->registerJs($model->addValidators($this,$attribute), 5);  ?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
    
    <?=\mihaildev\ckeditor\CKEditor::widget([
        'model'=>$model,
        'attribute'=>$attribute,
        'editorOptions' => ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
                'preset' => 'basic',
                'allowedContent' => true,
                'height'=>'200px',
        ])
    ])?>

    <div class="uk-form-help-block uk-text-danger"></div>

</div>