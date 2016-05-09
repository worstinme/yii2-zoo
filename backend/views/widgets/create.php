<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('backend', 'Созадние пункта меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Настройка меню'), 'url' => ['/'.Yii::$app->controller->module->id.'/menu/index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications items-create">

    <div class="uk-panel-box">

    <dl class="uk-description-list-line">
    <?php foreach ($widgets as $key=>$widget): ?>
    	
    		<dt><?= Html::a($widget::getName(), ['update','widget'=>$key]); ?></dt>
    		<dd><?=$widget::getDescription()?></dd>

    <?php endforeach ?>
    </dl>

    </div>

</div>