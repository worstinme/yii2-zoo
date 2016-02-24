<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('admin', 'Созадние пункта меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Настройка меню'), 'url' => ['/'.Yii::$app->controller->module->id.'/menu/index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications items-create">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

        <hr>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

	</div>

    <div class="uk-width-medium-1-5">
        <?=$this->render('/_nav')?>
    </div>


</div>

</div>
