<?php

use worstinme\uikit\Nav;
use yii\helpers\Html;


$items = [
    
    ['label' => '<i class="uk-icon-th-large"></i> '.Yii::t('backend','Все приложения'),
        'url' => ['/'.Yii::$app->controller->module->id.'/default/index'],], 
    ['label' => '<i class="uk-icon-navicon"></i> '.Yii::t('backend','Настройка меню'),
        'url' => ['/'.Yii::$app->controller->module->id.'/menu/index'],],

    '<li class="uk-nav-divider"></li>',

    ['label' => '<i class="uk-icon-clipboard"></i> '.Yii::t('backend','Материалы'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id],], 
    ['label' => '<i class="uk-icon-th-list"></i> '.Yii::t('backend','Категории'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/default/categories','app'=>Yii::$app->controller->app->id],], 
    ['label' => '<i class="uk-icon-cubes"></i> '.Yii::t('backend','Элементы'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/default/elements','app'=>Yii::$app->controller->app->id],], 
    ['label' => '<i class="uk-icon-object-group"></i> '.Yii::t('backend','Шаблоны'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/default/templates','app'=>Yii::$app->controller->app->id],], 

    '<li class="uk-nav-divider"></li>',

    ['label' => '<i class="uk-icon-edit"></i> '.Yii::t('backend','Создать категорию'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/default/update-category','app'=>Yii::$app->controller->app->id],],

    ['label' => '<i class="uk-icon-plus"></i> '.Yii::t('backend','Создать материал'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/items/create','app'=>Yii::$app->controller->app->id],],

    '<li class="uk-nav-divider"></li>',

    ['label' => '<i class="uk-icon-cog"></i> '.Yii::t('backend','Настройки'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/default/config','app'=>Yii::$app->controller->app->id],], 
];

?>

<div class="uk-panel uk-panel-box">

<?= Nav::widget([

    'options'=>['class'=>'uk-nav-side','data-uk-nav'=>true],

    'items' => $items,

]); ?>



</div>

<hr>