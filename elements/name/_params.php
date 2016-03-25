<?php

use yii\helpers\Html;

?>

<hr>

<form>
<?= Html::checkbox('asUrl', isset($params['asUrl']) ? $params['asUrl'] : false ,['label'=>'Отображать ссылку на материал?']); ?>
</form>