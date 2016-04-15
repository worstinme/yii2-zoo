<?php

use yii\helpers\Html;


?>

<hr>

<form>
<?= Html::checkbox('multiselect', isset($params['multiselect']) ? $params['multiselect'] : null,['label'=>'Мультиселект']); ?><br>
</form>