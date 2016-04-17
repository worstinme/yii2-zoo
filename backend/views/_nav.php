<?php

use worstinme\uikit\Nav;
use worstinme\uikit\NavBar;
use yii\helpers\Html;

$subnav = [

    ['label' => '<i class="uk-icon-plus"></i> '.Yii::t('backend','Создать материал'), 
        'linkOptions'=>['class'=>'uk-button-primary'],
        'url' => ['/'.Yii::$app->controller->module->id.'/items/create','app'=>Yii::$app->controller->app->id],],

    ['label' => '<i class="uk-icon-edit"></i> '.Yii::t('backend','Создать категорию'), 
        'url' => ['/'.Yii::$app->controller->module->id.'/categories/update','app'=>Yii::$app->controller->app->id],],

   
];

if (isset($items) && is_array($items) && count($items)) {
    $subnav = array_merge($items,$subnav);
}

?>
<div class="items-filters">
<? NavBar::begin(['container'=>false,'offcanvas'=>false,'brandUrl' => false,'options'=>['class'=>'application-nav']]); ?>
    
    <span class="uk-navbar-brand"><?=Yii::$app->controller->app->title?></span>

    <?=Nav::widget([
        'navbar'=>true,
        'options'=>['data-uk-nav'=>true],
        'items' => [

            ['label' => Yii::t('backend','Материалы'), 
                'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id],], 
            ['label' => Yii::t('backend','Категории'), 
                'url' => ['/'.Yii::$app->controller->module->id.'/categories/index','app'=>Yii::$app->controller->app->id],], 
            ['label' => Yii::t('backend','Элементы'), 
                'url' => ['/'.Yii::$app->controller->module->id.'/elements/index','app'=>Yii::$app->controller->app->id],], 
            ['label' => Yii::t('backend','Шаблоны'), 
                'url' => ['/'.Yii::$app->controller->module->id.'/templates/index','app'=>Yii::$app->controller->app->id],], 

            ['label' => '<i class="uk-icon-cog"></i>', 
                    'url' => ['/'.Yii::$app->controller->module->id.'/default/update','app'=>Yii::$app->controller->app->id],], 

        ],
    ]);  ?>

    <div class="uk-navbar-flip">

        <?= Nav::widget([
            'navbar'=>true,
            'options'=>['data-uk-nav'=>true],
            'items' => $subnav,
        ]); ?>

    </div>

<?php NavBar::end(); ?>
</div>