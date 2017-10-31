<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this, $attribute), 5);

$input_id = Html::getInputId($model, $attribute);

\worstinme\zoo\assets\Select2Asset::register($this);


?>
<div>
<?php if (!empty($element->admin_hint)): ?>
    <i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?= $input_id ?>'}"></i>
    <?= Html::activeLabel($model, $attribute, ['class' => 'uk-form-label']); ?>
    <p class="hint-<?= $input_id ?> uk-hidden">
        <?= $element->admin_hint ?>
    </p>
<?php else: ?>
    <?= Html::activeLabel($model, $attribute, ['class' => 'uk-form-label']); ?>
<?php endif ?>

<?= \worstinme\zoo\helpers\Select2Widget::widget([
    'model' => $model,
    'attribute' => $attribute,
    'options' => [
        'multiple'=>true,
    ],
    'settings' => [
        'tags' => true,
        'width' => '100%',
    ],
    'items'=>$model->{$attribute}+$element->tags,
]); ?>
</div>
