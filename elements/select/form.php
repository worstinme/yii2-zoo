<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,'name'), 5);

?>

<?= Html::activeLabel($model, 'name',['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
	<?= Html::activeInput('text', $model, $attribute,['class'=>'uk-width-1-1']); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
