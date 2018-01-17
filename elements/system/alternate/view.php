<?php

use yii\helpers\Html;
use yii\helpers\Url;

foreach ($model->alternates as $alternate) {
    Yii::$app->view->registerLinkTag(['rel' => 'alternate','hreflang'=>$alternate->lang, 'href' => Url::to($alternate->url, true)]);
}