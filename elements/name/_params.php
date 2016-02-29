<?php

use yii\helpers\Html;

?>

<hr>

<form>
<?= Html::checkbox('asUrl', $checked = false,['label'=>'Отображать ссылку на материал?']); ?>
</form>