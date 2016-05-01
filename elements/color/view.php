<?php


$values = $model->{$attribute};


foreach ($values as $key => $value) {
	if (!empty($value)) {
		$values[$key] = '<span class="'.$value.'">'.(isset(Yii::$app->params['catalog']['colors'][$value])?Yii::$app->params['catalog']['colors'][$value]:$value).'</span>';
	}
	else {
		unset($values[$key]);
	}
}

$value = implode(", ",$values);

?>

<?php if (!empty($value)): ?>
<span class="label"><?=$model->getAttributeLabel($attribute)?>:</span> <?=$value?>
<?php endif ?>