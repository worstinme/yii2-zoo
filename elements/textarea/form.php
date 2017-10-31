<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5); 

$input_id = Html::getInputId($model,$attribute);

?>

<?php if (!empty($element->admin_hint)): ?>
    <i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?=$input_id?>'}"></i>
    <?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?> 
    <p class="hint-<?=$input_id?> uk-hidden">
        <?=$element->admin_hint?>
    </p>    
<?php else: ?>
    <?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?> 
<?php endif ?>

<div class="uk-from-controls">
    
    <?=\mihaildev\ckeditor\CKEditor::widget([
        'model'=>$model,
        'attribute'=>$attribute,
        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder', 'path' => '/'],[
                'preset' => 'standart',
                'allowedContent' => true,
                'height'=>'200px',
                'toolbar' => Yii::$app->zoo->cke_editor_toolbar,
                'contentsCss'=>Yii::$app->zoo->cke_editor_css,
        ])
    ])?>

    <div class="uk-form-help-block uk-text-danger"></div>

</div>
