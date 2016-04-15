<?php

use yii\helpers\Html;


?>

<hr>

<form>
<?= Html::checkbox('branding', isset($params['branding']) ? $params['branding'] : false ,['label'=>'Кнопка на страницу брендинга?']); ?>
</form>

