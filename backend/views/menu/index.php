<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('backend', 'Menu');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications items-index">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">
 
    <div class="uk-panel uk-panel-box">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(Yii::t('backend','Добавить пункт меню'), ['/'.Yii::$app->controller->module->id.'/menu/update'], ['class' => 'uk-button uk-button-success']); ?>
    
    </div>

    </div>




</div>

</div>
