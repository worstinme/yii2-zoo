<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model, $element->attributeName);

\worstinme\zoo\backend\assets\Select2Asset::register($this);

?>

<?= Html::activeDropDownList($model, $element->attributeName, $element->variants, [
    'placeholder' => 'Choose item',
    'multiple' => true,
    'class' => 'uk-select'
]) ?>

<?php $script = <<<JS
    $('#$input_id').select2({width:'100%'});
JS;

$this->registerJs($script, $this::POS_READY) ?>
