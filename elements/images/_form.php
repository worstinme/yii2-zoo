<?php

use yii\helpers\Html;
use mihaildev\elfinder\InputFile;

$this->registerJs($model->addValidators($this,$attribute), 5);

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<?php foreach ($model->{$attribute} as $key=>$image): ?>
	<div class="uk-from-controls">
		<?= InputFile::widget([
		    'language'   => 'ru',
		    'controller' => 'elfinder', 
		    'model'       => $model,
		    'attribute'      => $attribute."[".$key."]",
		    'buttonName'=>'Выбрать',
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]); ?>
		<div class="uk-form-help-block uk-text-danger"></div>
	</div>	
<?php endforeach ?>

<div class="uk-from-controls">
	<?= InputFile::widget([
		    'language'   => 'ru',
		    'controller' => 'elfinder', 
		    'model'       => $model,
		    'attribute'      => $attribute."[]",
		    'buttonName'=>'Выбрать',
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]); ?>
	<div class="uk-form-help-block uk-text-danger"></div>
</div>
