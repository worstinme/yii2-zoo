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

                <?=Html::a('','#',['onClick' => "var link=$(this);$.ajax({url:'".\yii\helpers\Url::to(['update','app'=>$category->app_id, 'category'=>$category->id])."',type:'POST',data: {'".$category->formName()."[state]':link.data('state')==0?1:0},success: function(data){if (data.success) {if(data.model.state == 1) link.attr('class','uk-margin-left uk-icon-check-circle'); else link.attr('class','uk-margin-left uk-icon-times-circle'); link.data('state',data.model.state)}}})",'class'=>"uk-margin-left uk-icon-".($category->state==1 ? 'check' :'times')."-circle",'data'=>['pjax'=>0,'state'=>$category->state]])?>

                <?=Html::a('','#',['onClick' => "var link=$(this);$.ajax({url:'".\yii\helpers\Url::to(['update','app'=>$category->app_id, 'category'=>$category->id])."',type:'POST',data: {'".$category->formName()."[flag]':link.data('state')==0?1:0},success: function(data){if (data.success) {if(data.model.flag == 1) link.attr('class','uk-margin-left uk-icon-flag'); else link.attr('class','uk-margin-left uk-icon-flag-o'); link.data('state',data.model.flag)}}})",'class'=>"uk-margin-left uk-icon-".($category->flag==1 ? 'flag' :'flag-o'),'data'=>['pjax'=>0,'state'=>$category->flag]])?>

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