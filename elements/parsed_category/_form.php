<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
	<ul class="uk-list-line uk-list">
	<?php foreach ($model->{$attribute} as $value): ?>
		<li><?=$value?></li>
	<?php endforeach ?>
	</ul>
</div>
