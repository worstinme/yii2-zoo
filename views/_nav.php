<?php

use worstinme\uikit\Nav;
use yii\helpers\Html;
?>

<div class="uk-panel uk-panel-box">

<?= Nav::widget([

    'options'=>['class'=>'uk-nav-side','data-uk-nav'=>true],

    'items' => [
        
        ['label' => '<i class="uk-icon-th-large"></i> '.Yii::t('admin','Все приложения'),'url' => ['/'.Yii::$app->controller->module->id.'/default/index'],], 

        '<li class="uk-nav-divider"></li>',

        ['label' => '<i class="uk-icon-clipboard"></i> '.Yii::t('admin','Материалы'), 
        	'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>$app->id],], 
        ['label' => '<i class="uk-icon-th-list"></i> '.Yii::t('admin','Категории'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/default/categories','app'=>$app->id],], 
        ['label' => '<i class="uk-icon-cubes"></i> '.Yii::t('admin','Элементы'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/default/fields','app'=>$app->id],], 
        ['label' => '<i class="uk-icon-object-group"></i> '.Yii::t('admin','Шаблоны'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/default/templates','app'=>$app->id],], 

    	'<li class="uk-nav-divider"></li>',

        ['label' => '<i class="uk-icon-edit"></i> '.Yii::t('admin','Создать категорию'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/default/update-category','app'=>$app->id],],

        ['label' => '<i class="uk-icon-plus"></i> '.Yii::t('admin','Создать материал'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/items/create','app'=>$app->id],],

        '<li class="uk-nav-divider"></li>',

        ['label' => '<i class="uk-icon-cog"></i> '.Yii::t('admin','Настройки'), 
            'url' => ['/'.Yii::$app->controller->module->id.'/default/config','app'=>$app->id],], 


    ],

]); ?>



</div>

<hr>