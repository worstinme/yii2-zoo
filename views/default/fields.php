<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('admin','Категории');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="applications applications-index">
	<div class="uk-grid">
		<div class="uk-width-medium-4-5">
			
			<div class="uk-panel uk-panel-box">
				<p>TODO: Список элементов</p>
			</div>
			
			<hr>

			<?php if (count(Yii::$app->controller->modelFields)): ?>
				<?php foreach (Yii::$app->controller->modelFields as $name => $field): ?>
					<?=Html::a('<i class="'.$field->iconClass.'"></i> '.$field->fieldname,
						['/'.Yii::$app->controller->module->id.'/default/update-field',
								'field_name'=>$name,'app'=>$app->id],
						['class'=>'uk-button uk-button-success uk-border-rounded'])?>
				<?php endforeach ?>
			<?php endif ?>

		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('_nav',['app'=>$app])?>
		</div>
	</div>
</div>