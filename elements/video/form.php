<?php

use yii\helpers\Html;
use mihaildev\elfinder\InputFile;

$this->registerJs($model->addValidators($this,$attribute), 5);

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
	<?= Html::activeInput('text', $model, $attribute,['class'=>'uk-width-1-1']); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>