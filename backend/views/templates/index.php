<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('backend','Шаблоны отображения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?> 

<?=$this->render('/_nav')?>

<div class="uk-panel uk-panel-box">

	<h1 class="uk-h3"><?=$this->title?></h1>

	<ul class="uk-subnav uk-subnav-pill">
	<?php foreach ($templates as $key => $value): ?>
		<li>
			<?=Html::a($value,['template','template'=>$value,'app'=>Yii::$app->controller->app->id])?>
		</li>
	<?php endforeach ?>
	</ul>
	
</div>