<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model, $element->attributeName);

\worstinme\zoo\backend\assets\Select2Asset::register($this);

echo Html::activeDropDownList($model, $element->attributeName, $model->{$element->attributeName}+$element->tags, ['multiple' => true]);

$sript = <<<JS

$('#$input_id').select2({width:'100%','tags':true});

JS;

$this->registerJs($sript,$this::POS_READY);