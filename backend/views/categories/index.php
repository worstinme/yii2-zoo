<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('zoo', 'CATEGORIES_INDEX_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'APPLICATIONS_INDEX_BREADCRUMB'), 'url' => ['applications/index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['items/index', 'app' =>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

$parent_categories = \worstinme\zoo\backend\models\Categories::find()
    ->where(['app_id'=> $app->id, 'parent_id'=>0])
    ->orderBy('sort')
    ->indexBy('id')
    ->all();

?>

<?php if (count($parent_categories)): ?>
    <?= $this->render('_categories', [
        'categories' => $parent_categories,
        'parent_id' => 0,
    ]) ?>
<?php else: ?>
    <p><?=Yii::t('zoo','Create first category')?></p>
<?php endif ?>

<?php

$category_sort_url = Url::toRoute(['sort']);
$alias_create_url = Url::toRoute(['alias-create']);

$script = <<< JS

(function($){

	var nestable = UIkit.nestable('[data-uk-nestable]');
		
	nestable.on('change.uk.nestable',function(event,it,item,action){
		var category = {};
		category.id = item.data('item-id');
		category.parent_id = item.parent('[data-parent-id]').data('parent-id');
		var sort = {}; 
		item.parent('[data-parent-id]').children('li').each(function(index) {
			itemid = $(this).data('item-id');
			sort[itemid] = index;
		});
		category.sort = sort;
		console.log(category);

		$.post("$category_sort_url",category, function( data ) {
		 	console.log(data);
		 	UIkit.notify("Есть кптан");
		});
	});

	$("[data-aliascreate]").on('change',function(){
		var item = $(this),aliastarget = $($(this).data('aliascreate'));
		$.post('$alias_create_url',{alias:item.val()}, function(data) {
				aliastarget.val(data);
				aliastarget.trigger( "change" );
		});
	});

})(jQuery);

JS;

$this->registerJs($script, \yii\web\View::POS_END);