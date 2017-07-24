<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('zoo', 'Создание материала');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?=$this->render('/_nav',['model'=>$model])?> 
	

<?= $this->render('_form', [
    'model' => $model,
]) ?>