<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('backend', 'Создание материала');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

$template = Yii::$app->controller->app->getTemplate('full');



?>
<div class="applications items-create">

	<div class="uk-grid uk-grid-small">

	    <div class="uk-width-medium-5-6">
    	
	    	<?php 
	    	if (count($template)) {
			    foreach ($template as $row) {
			        if (count($row['items'])) {
			            echo $this->render('rows/'.$row['type'],[
			                'row'=>$row,
			                'model'=>$model,
			                'view'=>'_view',
			            ]);    
			        }
			    }
			} ?>

	    </div>

	    <div class="uk-width-medium-1-6">
	        <?=$this->render('/_nav')?>
	    </div>

	</div>

</div>
