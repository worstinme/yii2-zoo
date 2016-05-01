<?php

$format = !empty($params['format']) ? $params['format'] : 'php:d.m.Y';

if (isset($params['label']) && $params['label']) {
	echo '<span class="label">'.$model->getAttributeLabel($attribute).'</span> ';
} 

echo Yii::$app->formatter->asDate($model->{$attribute},$format);