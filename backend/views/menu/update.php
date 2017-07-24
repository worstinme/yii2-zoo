<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('zoo', 'Созадние пункта меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Настройка меню'), 'url' => ['/'.Yii::$app->controller->module->id.'/menu/index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications items-create">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

        <div class="uk-panel-box">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

        </div>

	</div>


</div>

</div>
