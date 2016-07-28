<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->registerJs($model->addValidators($this,$attribute), 5);

$multiselect = isset($params['multiselect']) ? (int)$params['multiselect'] : 0;

?>

<?php if ($multiselect && count($model->app->catlist)): ?>

	<?= Html::activeLabel($model, 'category',['class'=>'uk-form-label']); ?>

	<?=Html::activeDropDownList($model,$attribute,$model->app->catlist,['class'=>'uk-width-1-1','multiple'=>'multiple','size'=>10])?>

<?php elseif(($parent_categories = ArrayHelper::map(Yii::$app->controller->app->parentCategories,'id','name')) !==null && count($parent_categories)): ?>

	<?= Html::activeLabel($model, 'category',['class'=>'uk-form-label']); ?>
		
	<div class="uk-form-controls">
		<?=Html::activeDropDownList($model,$attribute.'[0]',$parent_categories,['id'=>'item-'.$attribute,'prompt'=>'выбрать из списка','class'=>'uk-width-1-1 category-select'])?>
		<?php if (count($model->category)): ?>
		<?php $i=0;foreach ($model->category as $key => $category_id): ?>
			<?php if ($category_id > 0): ?>		
				<? $related_cats = ArrayHelper::map(\worstinme\zoo\models\Categories::find()->where(['parent_id'=>$category_id])->all(),'id','name'); ?>
				<?php if (count($related_cats)): $i++; ?>
				<div class="related-category-select uk-margin-top">
					<?=Html::activeDropDownList($model,$attribute.'['.($key+1).']',$related_cats,['prompt'=>'выбрать из списка','class'=>'uk-width-1-1  category-select'])?>	
				<?php endif ?>
			<?php endif ?>
		<?php endforeach ?>

		<?php while ($i > 0) {
			echo '</div>'; $i--;
		} ?>
			
		<?php endif ?>
		<div class="uk-form-help-block uk-text-danger"></div>
	</div>

<?php endif; ?>