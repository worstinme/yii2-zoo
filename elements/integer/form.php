<?php

use yii\helpers\Html;

echo Html::activeInput('number', $model, $attribute, ['step'=>1, 'class' => 'uk-width-1-1']);