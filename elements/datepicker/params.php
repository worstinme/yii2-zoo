<?php

use yii\helpers\Html;


?>

<form>
<?= Html::checkbox('label', isset($params['label']) ? $params['label'] : null,['label'=>'Отображать название поля?']); ?><br>
<?= Html::textInput('format', isset($params['format']) ? $params['format'] : 'php:d.m.Y', ['placeholder' => 'Формат']); ?>
</form>