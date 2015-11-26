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
			
			<?=Html::a('Создать элемент',
						['/'.Yii::$app->controller->module->id.'/default/create-field','app'=>$app->id],
						['class'=>'uk-button uk-button-success uk-border-rounded'])?>

			<?php if (count($app->fields)): ?>
				<table class="uk-table uk-table-striped uk-table-condensed uk-table-middle uk-table-hover">
				<thead>
					<tr>	
						<td>Название</td>
						<td></td>
						<td>Тип</td>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($app->fields as $key => $field): ?>
				<tr>	
					<td><?=$field->title?></td>
					<td><?=$field->name?></td>
					<td><?=$field->type?></td>
					<td>
					<?=Html::a('<i class="uk-icon-edit"></i>',['/'.Yii::$app->controller->module->id.'/default/update-field',
								'field'=>$field->id,'app'=>$app->id],['class'=>'uk-button uk-button-success uk-button-mini'])?>
					<?=Html::a('<i class="uk-icon-trash"></i>',['/'.Yii::$app->controller->module->id.'/default/delete-field',
								'field'=>$field->id,'app'=>$app->id],['class'=>'uk-button uk-button-danger uk-button-mini','data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'data-method' => 'post', 'data-pjax' => '0',])?>		
					</td>
				</tr>
				<?php endforeach ?>
				</tbody>
				</table>
			<?php endif ?>

		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('/_nav',['app'=>$app])?>
		</div>
	</div>
</div>