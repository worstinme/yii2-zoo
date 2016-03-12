<?php

use yii\helpers\Html;


?>

<hr>

<form>
<?= Html::checkbox('preview', $params['preview'],['label'=>'Отобразить превью первой картинки']); ?><br>
<?= Html::checkbox('asUrl', $params['asUrl'],['label'=>'Отображать ссылку на материал?']); ?><br>
<?= Html::checkbox('gallery', $params['gallery'],['label'=>'Отображать в виде галлереи??']); ?><br>
<?= Html::textInput('width',$params['width'], ['placeholder' => 'Ширина']); ?><br>
<?= Html::textInput('height',$params['height'], ['placeholder' => 'Высота']); ?><br>
</form>