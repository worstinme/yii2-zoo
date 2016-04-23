<?php

use yii\helpers\Html; 

$tag = !empty($params['tag']) ? $params['tag'] : 'h1';

$content = !empty($params['asUrl']) && $params['asUrl'] == 1 ? Html::a($model->name, $model->url,['data-pjax'=>0]) : $model->name;

echo Html::tag($tag,$content);