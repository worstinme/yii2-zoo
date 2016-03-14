<?php

use yii\helpers\Html;

?>

<?= Html::textInput('Cart[count]', $value = null); ?>
<?= Html::hiddenInput('Cart[item_id]', $model->id); ?>
<?= Html::a('Заказать', $url = null, ['class' => '']); ?>