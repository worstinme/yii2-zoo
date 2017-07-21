<?php

use yii\helpers\Html;

echo implode(", ", array_map(function ($i) use($element) {
    return $element->variants[$i] ?? $i;
}, $model->{$attribute}));