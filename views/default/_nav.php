<?php

use worstinme\uikit\Nav;
use yii\helpers\Html;
?>

<div class="uk-panel uk-panel-box">

<?= Nav::widget([

    'options'=>['class'=>'uk-nav-side','data-uk-nav'=>true],

    'items' => [
        
        ['label' => $app->title,'url' => ['application','app'=>$app->id],], 

        '<li class="uk-nav-divider"></li>',

        ['label' => Yii::t('admin','Материалы'), 
        	'url' => ['/'.Yii::$app->controller->module->id.'/default/application','app'=>$app->id],], 
        ['label' => Yii::t('admin','Категории'), 
        	'url' => ['/'.Yii::$app->controller->module->id.'/default/categories','app'=>$app->id],], 

    	'<li class="uk-nav-divider"></li>',

    	['label' => '<i class="uk-text-success uk-icon-plus"></i> '.Yii::t('admin','Создать категорию'), 
    		'url' => ['/'.Yii::$app->controller->module->id.'/default/update-category','app'=>$app->id],], 

    ],

]); ?>



</div>

<hr>