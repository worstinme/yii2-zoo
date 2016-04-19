<?php

use yii\helpers\Html;


?>

<hr>

<form>
<?= Html::checkbox('asUrl', isset($params['asUrl']) ? $params['asUrl'] : null,['label'=>'Отображать ссылку на материал?']); ?><br>
<?= Html::textInput('width', isset($params['width']) ? $params['width'] : null, ['placeholder' => 'Ширина']); ?><br>
<?= Html::textInput('height', isset($params['height']) ? $params['height'] : null, ['placeholder' => 'Высота']); ?><br>
</form>