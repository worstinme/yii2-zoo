<?php

use yii\helpers\Html;

?>

<hr>

<form>
<?= Html::checkbox('asUrl', isset($params['asUrl']) ? $params['asUrl'] : false ,['label'=>'Отображать ссылку на материал?']); ?><br>
<?= Html::textInput('tag', isset($params['tag']) ? $params['tag'] : null, ['placeholder' => 'Тег']); ?>
</form>