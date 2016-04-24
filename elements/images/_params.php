<?php

use yii\helpers\Html;


?>

<hr>

<form>
<?= Html::checkbox('preview', isset($params['preview']) ? $params['preview'] : null,['label'=>'Отобразить превью первой картинки']); ?><br>
<?= Html::checkbox('asUrl', isset($params['asUrl']) ? $params['asUrl'] : null,['label'=>'Отображать ссылку на материал?']); ?><br>
<?= Html::checkbox('gallery', isset($params['gallery']) ? $params['gallery'] : null,['label'=>'Отображать в виде галлереи??']); ?><br>
<?= Html::textInput('width', isset($params['width']) ? $params['width'] : null, ['placeholder' => 'Ширина']); ?><br>
<?= Html::textInput('height', isset($params['height']) ? $params['height'] : null, ['placeholder' => 'Высота']); ?><br>
<?= Html::checkbox('wall', isset($params['wall']) ? $params['wall'] : null,['label'=>'wall']); ?><br>
<?= Html::checkbox('lightbox', isset($params['lightbox']) ? $params['lightbox'] : null, ['label' => 'lightbox']); ?><br>
</form>