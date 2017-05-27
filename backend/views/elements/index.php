<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('backend','Категории');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?=$this->render('/_nav',['app'=>$app, 'items' => [
	['label'=>'Создать новый элемент','url'=>['create','app'=>$app->id],'linkOptions'=>['class'=>'uk-button-success']],
]])?>

<?php if (count($app->elements)): ?>
	<table class="items uk-table uk-table-striped uk-table-condensed uk-table-middle uk-table-hover">
	<tbody>
	<?php foreach ($app->elements as $key => $element): ?>
	<tr>	
		<td><?=Html::a($element->label,['update','element'=>$element->id,'app'=>$app->id])?></td>
		<td><?=$element->name?></td>
		<td><?=$element->type?></td>
		<td class="uk-text-right">
			<?=Html::a('<i class="uk-icon-trash"></i>',['delete','element'=>$element->id,'app'=>$app->id],
				['class'=>'uk-button uk-button-danger uk-button-mini','data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post', 'data-pjax' => '0',])?>		
		</td>
	</tr>
	<?php endforeach ?>
	</tbody>
	</table>
<?php endif ?>