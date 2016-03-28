<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="items-filter">

	<?php $form = ActiveForm::begin([
		'action'=> yii\helpers\Url::current(),
        'method' => 'get',
        'options'=>['class'=>'','data-pjax' => true],
    ]); ?>

    <h4>Подбор по характеристикам</h4> 

	<?php foreach ($app->elements as $element): ?>
		<?php if ($element->filter == 1): ?>
			<div class="filter"><?= $this->render('@worstinme/zoo/elements/'.$element->type.'/_filter.php',['element'=>$element,'searchModel'=>$searchModel,'form'=>$form]); ?></div>
		<?php endif ?>
	<?php endforeach ?>

    <div class="form-group uk-margin-top uk-text-center">
        <?= Html::submitButton('Поиск', ['class' => 'submit-button']) ?>
    </div>

	<?php ActiveForm::end(); ?>
 
</div>