<?php

use yii\helpers\Html;

?>

<hr>

<form>
<?= Html::checkbox('preview', $checked = false,['label'=>'Отобразить превью первой картинки']); ?><br>
<?= Html::checkbox('asUrl', $checked = false,['label'=>'Отображать ссылку на материал?']); ?><br>
<?= Html::checkbox('gallery', $checked = false,['label'=>'Отображать в виде галлереи??']); ?>
</form>