<?php
//
use yii\helpers\Html;

$related = isset($related) ? $related : false;

?>
<ul class="<?=$related ? 'uk-nestable-list' : 'uk-nestable'?>"<?=$related ? '' :' data-uk-nestable="{handleClass:\'uk-nestable-handle\'}"'?> data-parent-id="<?=$parent_id?>">
	<?php foreach ($categories as $category): ?>
	    <li class="uk-collapsed uk-nestable-item" data-item-id="<?=$category->id?>">
	        <div class="uk-nestable-panel">
	            <i class="uk-nestable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
	            <div data-nestable-action="toggle" class="uk-nestable-toggle uk-margin-small-right"></div>
		        <?=Html::a($category->name,['update','app'=>$category->app_id,'category'=>$category->id])?> / <?=$category->alias?> 
		        <?=Html::a('<i class="uk-icon-trash"></i>',['delete','app'=>Yii::$app->controller->app->id,'category'=>$category->id],[
		        		'class'=>'uk-float-right uk-margin-right',
		        		'data'=>[
		        			'method'=>'post',
		        			'confirm'=>'Уверены что хотите удалить категорию '.$category->name.'?',
		        		],
		        ])?> 
		        <i class="uk-float-right uk-margin-right"><?=$category->id?></i>
			</div>
			<?php if ($category->getRelated()->count()): ?>
		        <?= $this->render('_categories', [
				    'categories' => $category->related,
				    'related' =>true,
				    'parent_id'=>$category->id,
				]) ?>
		    <?php endif ?>
		</li>					
	<?php endforeach ?>
</ul>