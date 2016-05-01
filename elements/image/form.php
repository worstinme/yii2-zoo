<?php

use yii\helpers\Html;
use mihaildev\elfinder\InputFile;

$this->registerJs($model->addValidators($this,$attribute), 5);

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
	<?= InputFile::widget([
		    'language'   => 'ru',
		    'controller' => 'elfinder', 
		    'model'       => $model,
		    'attribute'      => $attribute,
		    'buttonName'=>'Выбрать',
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
