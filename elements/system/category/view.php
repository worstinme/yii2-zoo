<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

if (count($model->categories))

foreach ($model->categories as $category) {
	echo Html::a($category->name, $category->url);
} 