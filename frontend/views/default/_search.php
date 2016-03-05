<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="items-search">

	<?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

	<?php foreach ($app->elements as $element): ?>
		<?php if ($element->filter == 1): ?>
			<?= $this->render('@worstinme/zoo/elements/'.$element->type.'/_filter.php',['element'=>$element]); ?>
		<?php endif ?>
	<?php endforeach ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>
 
</div>