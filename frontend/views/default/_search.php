<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="items-filter">

	<?php $form = ActiveForm::begin([
        'method' => 'get',
        'options'=>['class'=>''],
    ]); ?>

    <h4>Подбор по характеристикам</h4> 

	<?php foreach ($app->elements as $element): ?>
		<?php if ($element->filter == 1): ?>
			<div class="filter">
			<?= $this->render('@worstinme/zoo/elements/'.$element->type.'/_filter.php',['element'=>$element,'searchModel'=>$searchModel,'form'=>$form]); ?>
			</div>
		<?php endif ?>
	<?php endforeach ?>

    <div class="form-group uk-margin-top">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>
 
</div>