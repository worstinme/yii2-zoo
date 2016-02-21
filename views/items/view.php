<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('admin', 'Создание материала');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/default/application','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

$template = Yii::$app->controller->app->getTemplate('full');



?>
<div class="applications items-create">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

        <hr>
    	
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

    <div class="uk-width-medium-1-5">
        <?=$this->render('/_nav')?>
    </div>


</div>

</div>
