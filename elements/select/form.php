<?php

use yii\helpers\Html;

$variants = is_array($element->variants) ? $element->variants : [];
$input_id = Html::getInputId($model,$element->attributeName);


?>

<?= Html::activeDropDownList($model, $element->attributeName, $variants, ['class' => 'uk-select']); ?>
