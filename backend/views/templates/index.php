<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('backend','Шаблоны отображения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?> 

<?=$this->render('/_nav')?>

<h1 class="uk-h3"><?=$this->title?></h1>

<ul class="uk-subnav uk-subnav-pill">
	<li class="uk-disabled uk-padding-remove"><span class="uk-text-bold"><?=Yii::t('backend','Стандартные шаблоны')?></span></li>
<?php foreach ($templates as $key => $value): ?>
	<li>
		<?=Html::a($value,['renderer','template'=>$value,'app'=>Yii::$app->controller->app->id])?>
	</li>
<?php endforeach ?>
</ul>

<?php if (count($custom_templates)): ?>

<ul class="uk-subnav uk-subnav-pill">
	<li class="uk-disabled uk-padding-remove"><span class="uk-text-bold"><?=Yii::t('backend','Свои шаблоны')?></span></li>
<?php foreach ($custom_templates as $key => $value): ?>
	<li>
		<?=Html::a($value,['renderer','template'=>$value,'app'=>Yii::$app->controller->app->id])?>
	</li>
<?php endforeach ?>
</ul>


<?php endif ?>