<?php

use yii\helpers\Html;

\worstinme\uikit\assets\Nestable::register($this);
\worstinme\uikit\assets\Notify::register($this);

$this->title = Yii::t('backend','Шаблон отображения <em>'.$name.'</em>');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?> 

<?=$this->render('/_nav')?>

<h1 class="uk-h3"><?=$this->title?></h1>


<div class="renderers uk-grid">
	<div class="uk-width-medium-4-5">
			<?=$this->render('config',[
				'name'=>$name,
				'template'=>$template,
				'elements'=>Yii::$app->controller->app->elements,
				'url'=>\yii\helpers\Url::toRoute(['template-save','app'=>Yii::$app->controller->app->id]),
			]); ?>
	</div>
	<div class="uk-width-medium-1-5">
		<?php if (count(Yii::$app->controller->app->elements)): ?>
		<ul id="elements" class="uk-nestable">
		<?php foreach (Yii::$app->controller->app->elements as $element): ?>
			<?=$this->render('_element',['element'=>$element,'params'=>[]])?>		
		<?php endforeach ?>	
		</ul>
		<?php endif ?>
	</div>
</div>