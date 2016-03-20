<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm; 

$filter  = Yii::$app->request->post('Filter');

if ($filter['element'] == 'parsed_category') {
	
	$categories = (new \yii\db\Query())
	    ->select(['value_string'])
	    ->from('{{%zoo_items_elements}}')
	    ->where(['element'=>'parsed_category'])
	    ->groupBy('value_string')
	    ->orderBy('count(item_id) DESC')
	    ->column(); 

	$variants = [];

	foreach ($categories as $value) {
	  	
	  	$category = \app\modules\admin\models\ParserCategories::find()->where(['source_id'=>$value])->one();

	  	$cat_id = $value;

		if ($category !== null) {
			$value = $category->name;
			if ($category->parent !== null) {
				$value = $category->parent->name.' / '.$value;
				if ($category->parent->parent !== null) {
					$value = $category->parent->parent->name.' / '.$value;
				}
			}
		}

		$variants[$cat_id] = $value;
	}

}
else {

	$variants = $filter['element'] !== null ? (new \yii\db\Query())
	    ->select(['ie.value_string'])
	    ->from(['ie'=>'{{%zoo_items_elements}}'])
	    ->leftJoin(['i'=>'{{%zoo_items}}'],'i.id = ie.item_id')
	    ->where(['ie.element'=>$filter['element'],'i.app_id'=>Yii::$app->controller->app->id])
	    ->groupBy('value_string')
	    ->orderBy('count(item_id) DESC')
	    ->column() : []; 

	$variants = ArrayHelper::index($variants, function ($element) {return $element;}); 
}





$search_params = Yii::$app->request->get('ItemsSearch',[]);
?>

<div>

	<div class="uk-width-3-10">

		<?php $form = ActiveForm::begin(['action'=>Url::current(), 'id' => 'login-form','options'=>['data-pjax'=>false]]); ?>

			<?= Html::dropDownList('Filter[element]',$filter['element']?:null, ArrayHelper::map($searchModel->elements,'name','title'), ['id'=>"filter-element",'prompt'=>'Выбрать параметр для фильтрации']); ?>

		<?php ActiveForm::end(); ?>

	</div>

	<hr>

	<?php $form = ActiveForm::begin([
		'action'=> Url::current(),
        'method' => 'get',
        'options'=>['class'=>'','data-pjax' => true],
    ]); ?>

    <?php if (count($search_params)): ?>
    	<div class="filter-list">
		<?php foreach ($search_params as $key => $value): ?>
			<?php if (isset($searchModel->elements[$key])): ?>
				<?php if (is_array($value)): ?>

					<?php foreach ($value as $v): ?>
						<span class="uk-badge uk-badge-notification uk-badge-success">
							<b><?=$searchModel->elements[$key]->title?></b> : <?=$v?>
							<a href="#" style="color:#fff" data-name="ItemsSearch[<?=$key?>]" data-value="<?=$v?>">
								<i class="uk-icon-close"></i></a>
						</span>
					<?php endforeach ?>
				<?php else: ?>
					<span class="uk-badge uk-badge-notification uk-badge-success">
						<b><?=$searchModel->elements[$key]->title?></b> : <?=$value?>
						<a href="#" style="color:#fff" data-name="ItemsSearch[<?=$key?>]" data-value="<?=$value?>">
							<i class="uk-icon-close"></i></a>
					</span>
				<?php endif ?>
			<?php endif ?>
		<?php endforeach ?>
		</div>
	<?php endif; ?>


	<?php if (count($variants)): ?>
	
	<div class="uk-grid uk-grid-collapse uk-margin-top">	
		
	    <div class="uk-width-5-10">
			<?= Html::activeDropDownList($searchModel, $filter['element'].'[]', $variants, ['class' => 'uk-width-1-1 ']); ?>
		</div>
		
		<div>
			<?= Html::submitButton('Добавить', ['class' => 'uk-width-1-1 uk-button uk-button-success']) ?>
		</div>
	    
	 </div> 
	
	<?php endif ?>

	<?php ActiveForm::end(); ?>

		
	



</div>



<?php

$script = <<< JS

(function($){

	$("#filter-element").on('change',function(){ $(this).parents('form').submit(); });

	$("body").on("click",".uk-badge-notification a",function() {
		var badge = $(this),
			form = badge.parents('form'), 
			value = badge.data('value'), 
			name  = badge.data('name');

		form.find("[name^='" + name + "']").each(function() {
			if ($(this).val() == value) {
				$(this).remove(); badge.parents('span.uk-badge').remove();
			}
		})

		form.submit();
	});

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_END);