<?php

use yii\helpers\Html;

echo Html::activeInput('number', $model, $element->attributeName, ['step'=>1, 'class' => 'uk-input']);