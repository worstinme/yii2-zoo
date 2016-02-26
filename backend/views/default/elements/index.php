<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('backend','Категории');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="applications applications-index">
	<div class="uk-grid uk-grid-small">
		<div class="uk-width-medium-5-6">
			<div class="items">
				<?php if (count($app->elements)): ?>
					<table class="uk-table uk-table-striped uk-table-condensed uk-table-middle uk-table-hover">
					<tbody>
					<?php foreach ($app->elements as $key => $element): ?>
					<tr>	
						<td><?=$element->title?></td>
						<td><?=$element->name?></td>
						<td><?=$element->type?></td>
						<td class="uk-text-right">
						<?=Html::a('<i class="uk-icon-edit"></i>',['/'.Yii::$app->controller->module->id.'/default/update-element',
									'element'=>$element->id,'app'=>$app->id],['class'=>'uk-button uk-button-success uk-button-mini'])?>
						<?=Html::a('<i class="uk-icon-trash"></i>',['/'.Yii::$app->controller->module->id.'/default/delete-element',
									'element'=>$element->id,'app'=>$app->id],['class'=>'uk-button uk-button-danger uk-button-mini','data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
	                                            'data-method' => 'post', 'data-pjax' => '0',])?>		
						</td>
					</tr>
					<?php endforeach ?>
					</tbody>
					</table>
				<?php endif ?>
			</div>

			<div class="uk-margin-top">

					<?=Html::a('Создать элемент',
							['/'.Yii::$app->controller->module->id.'/default/create-element','app'=>$app->id],
							['class'=>'uk-button uk-button-success uk-border-rounded'])?>

			</div>

		</div>
		<div class="uk-width-medium-1-6">
			<?=$this->render('/_nav',['app'=>$app])?>
		</div>
	</div>
</div>