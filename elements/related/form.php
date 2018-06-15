<?php

use yii\helpers\Html;

worstinme\zoo\backend\assets\Select2Asset::register($this);

$input_id = Html::getInputId($model,$element->attributeName);

$this->registerJs("$('#".$input_id."').select2({width:'100%','placeholder':'Выбрать связанную страницу'});",$this::POS_READY);

echo Html::activeDropDownList($model, $element->attributeName, $element->items,['multiple'=>'multiple']); ?>