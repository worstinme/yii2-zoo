<span class="label"><?=$model->getAttributeLabel($attribute)?>:</span> OR<?=str_pad($model->id, 7,"0",STR_PAD_LEFT)?>
<?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('moder')): ?>
	<br><span class="label">Арт.пост:</span> <?=$model->{$attribute}?><br>
	<span class="label">ID:</span> <?=$model->source?>
<?php endif ?>