<?php

use yii\helpers\Html;


?>

<form>
<?= Html::textInput('width', isset($params['width']) ? $params['width'] : null, ['placeholder' => 'Ширина']); ?><br>
<?= Html::textInput('height', isset($params['height']) ? $params['height'] : null, ['placeholder' => 'Высота']); ?><br>
</form>