<?php

use yii\helpers\Html;


?>

<form>
<?= Html::checkbox('branding', isset($params['branding']) ? $params['branding'] : false ,['label'=>'Кнопка на страницу брендинга?']); ?>
</form>

