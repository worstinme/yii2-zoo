<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$parent_categories = ArrayHelper::map($app->parentCategories,'id','name');

?>

<label class="uk-form-label"><?=$field->title?></label>
<div class="uk-form-controls">
<?=Html::dropDownList('Items[categories][0]',$model->categories,$parent_categories,['prompt'=>'выбрать из списка','class'=>'uk-width-1-1 category-select'])?>
<?php if (count($model->categories)): ?>

<?php $i=0;foreach ($model->categories as $key => $category_id): ?>
	<?php if ($category_id > 0): ?>		
		<? $related_cats = ArrayHelper::map(\worstinme\zoo\models\Categories::find()->where(['parent_id'=>$category_id])->all(),'id','name'); ?>
		<?php if (count($related_cats)): $i++; ?>
		<div class="related-category-select uk-margin-top">
			<?=Html::dropDownList('Items[categories]['.($key+1).']',$model->categories,$related_cats,['prompt'=>'выбрать из списка','class'=>'uk-width-1-1  category-select'])?>	
		
		<?php endif ?>
	<?php endif ?>
<?php endforeach ?>

<?php while ($i > 0) {
	echo '</div>'; $i--;
} ?>
	
<?php endif ?>


</div>