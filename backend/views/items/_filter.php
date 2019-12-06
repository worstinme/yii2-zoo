<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\zoo\widgets\ActiveForm;
use app\modules\admin\models\ParserCategories;

$filter  = Yii::$app->request->post('Filter');

$elements = [];

$elementnames = ArrayHelper::map($searchModel->elements,'name','label');

foreach ($searchModel->elements as $element) {
	if (!in_array($element->name,$searchModel->attributes())) {

		if ($element->admin_filter) {
			$elements[$element->name] = $element->label;
		}

	}
}

if ($filter['element'] == 'parsed_category') {

	$categories = (new \yii\db\Query())
	    ->select(['value_string'])
	    ->distinct()
	    ->from('{{%items_elements}}')
	    ->where(['element'=>'parsed_category']);

	$categories = $searchModel->withoutCategory ? $categories->andWhere('item_id NOT IN (SELECT DISTINCT item_id FROM {{%items_categories}} WHERE 1)')->column(): $categories->column();

	$variants = [];

	foreach ($categories as $value) {

	  	$category = ParserCategories::find()->where(['source_id'=>$value])->one();

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

	asort($variants);

}
else {

	$variants = $filter['element'] !== null ? (new \yii\db\Query())
	    ->select(['ie.value_string'])
	    ->from(['ie'=>'{{%items_elements}}'])
	    ->leftJoin(['i'=>'{{%items}}'],'i.id = ie.item_id')
	    ->where(['ie.element'=>$filter['element'],'i.app_id'=>Yii::$app->controller->app->id])
	    ->groupBy('value_string')
	    ->orderBy('count(item_id) DESC')
	    ->column() : [];

	$variants = ArrayHelper::index($variants, function ($element) {return $element;});

/*	$variants = [];

	if ($filter['element'] !== null) {

		$varQuery = clone $searchModel->query;

		$attribute_sq = $filter['element'].'.value_string';

		$j = false; if(count($varQuery->join) > 0) foreach ($varQuery->join as $join)  if (isset($join[1][$element->name])) $j = true;

		if (!$j) $varQuery->leftJoin([$filter['element']=>'{{%items_elements}}'], $filter['element'].".item_id = a.id AND ".$filter['element'].".element = '".$filter['element']."'");

		$variants = $varQuery->select($attribute_sq)
		                ->groupBy($attribute_sq)
		                ->andWhere($attribute_sq.' IS NOT NULL')
		                ->orderBY('count('.$attribute_sq.') DESC')
		                ->column();

		$variants = ArrayHelper::index($variants, function ($element) {return $element;});

	} */
}


$search_params = Yii::$app->request->get('BackendItemsSearch',[]);

?>

<div>

	<div class="uk-width-3-10">

		<?php $form = ActiveForm::begin(['action'=>Url::current(), 'id' => 'login-form','options'=>['data-pjax'=>true]]); ?>

			<?= Html::dropDownList('Filter[element]',$filter['element']?:null, $elements, ['id'=>"filter-element",'prompt'=>'Выбрать параметр для фильтрации']); ?>

		<?php ActiveForm::end(); ?>

	</div>



	<?php $form = ActiveForm::begin([
		'action'=> Url::current(),
        'method' => 'get',
        'options'=>['class'=>'uk-margin-top','data-pjax' => true],
    ]); ?>

    <?= Html::activeCheckbox($searchModel, 'withoutCategory', ['labelOptions'=>[
    	'class' => 'uk-button uk-float-right',
    	'style'=>'margin-top:-45px;'
    ]]); ?>

    <?php if (count($search_params)): ?>
    	<div class="filter-list">
		<?php foreach ($search_params as $key => $value): ?>
			<?php if (isset($searchModel->elements[$key])): ?>
				<?php if (is_array($value)): ?>



					<?php foreach ($value as $v): ?>

						<?php if ($key == 'parsed_category'): ?>

							<?php $pc = ParserCategories::find()->where(['source_id'=>$v])->one() ?>

							<span class="uk-badge uk-badge-notification uk-badge-success">
								<b><?=$searchModel->elements[$key]->label?></b> : <?=$pc !== null? $pc->name : $v?>
								<a href="#" style="color:#fff" data-name="ItemsSearch[<?=$key?>]" data-value="<?=$v?>">
									<i class="uk-icon-close"></i></a>
							</span>

						<?php else: ?>

						<span class="uk-badge uk-badge-notification uk-badge-success">
							<b><?=$searchModel->elements[$key]->label?></b> : <?=$v?>
							<a href="#" style="color:#fff" data-name="ItemsSearch[<?=$key?>]" data-value="<?=$v?>">
								<i class="uk-icon-close"></i></a>
						</span>

						<?php endif ?>

					<?php endforeach ?>

				<?php else: ?>

					<?php if ($key == 'parsed_category'): ?>

						<?php $pc = ParserCategories::find()->where(['source_id'=>$value])->one() ?>

							<span class="uk-badge uk-badge-notification uk-badge-success">
								<b><?=$searchModel->elements[$key]->label?></b> : <?=$pc !== null? $pc->name : $value?>
								<a href="#" style="color:#fff" data-name="ItemsSearch[<?=$key?>]" data-value="<?=$value?>">
									<i class="uk-icon-close"></i></a>
							</span>

					<?php else: ?>

					<span class="uk-badge uk-badge-notification uk-badge-success">
						<b><?=$searchModel->elements[$key]->label?></b> : <?=$value?>
						<a href="#" style="color:#fff" data-name="ItemsSearch[<?=$key?>]" data-value="<?=$value?>">
							<i class="uk-icon-close"></i></a>
					</span>

					<?php endif ?>

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
	$("#itemssearch-withoutcategory").on('change',function(){ $(this).parents('form').submit(); });

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
