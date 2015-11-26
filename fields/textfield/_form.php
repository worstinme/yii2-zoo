<?php

use yii\helpers\Html;

?>

<?= Html::activeLabel($field, $field->name,['class'=>'uk-form-label']); ?>
<div class="uk-from-controls">
	<?= Html::activeInput('text', $field, $field->name, ['option' => $field->value]); ?>
</div>
