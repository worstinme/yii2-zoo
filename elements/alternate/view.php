<?php

use yii\helpers\Html;
use yii\helpers\Url;

$alternates = \worstinme\zoo\models\Items::find()->where(['id'=>$model->alternate])->all();

foreach ($alternates as $alternate) {
    Yii::$app->view->registerLinkTag(['rel' => 'alternate','hreflang'=>$alternate->lang, 'href' => Url::to($alternate->url, true)]);
}