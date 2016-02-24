<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('admin', 'Menu');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications items-index">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">
 
    <div class="uk-panel uk-panel-box">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(Yii::t('admin','Добавить пункт меню'), ['/'.Yii::$app->controller->module->id.'/menu/update'], ['class' => 'uk-button uk-button-success']); ?>
    
    </div>

    </div>

    <div class="uk-width-medium-1-5">
        <?=$this->render('/_nav')?>
    </div>


</div>

</div>
