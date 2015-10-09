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

			<?php $form = ActiveForm::begin(['action'=>['/'.Yii::$app->controller->module->id.'/default/update-category'],
					'id' => 'login-form','layout'=>'stacked',
					'field_width'=>'large']); ?>
			                    
			    <div class="uk-grid uk-grid-small">

			    <div class="uk-width-4-10">
			    <?= $form->field($model, 'name')->textInput(['placeholder'=>$model->getAttributeLabel('name'),"data-aliascreate"=>"#categories-alias"])->label(false)  ?>
			    </div>
			    <div class="uk-width-4-10">
			    <?= $form->field($model, 'alias')->textInput(['placeholder'=>$model->getAttributeLabel('alias')])->label(false)  ?>
			    </div>   
			    <div class="uk-width-2-10">          
			    <div class="uk-form-row">
			        <?= Html::submitButton(Yii::t('admin','Создать'),['class'=>'uk-button uk-button-success uk-width-1-1']) ?>
			    </div>
			    </div>
			    </div>
			<?php ActiveForm::end(); ?>

			<hr>
			
			<?php if (count($app->parentCategories)): ?>
			
				<?= $this->render('_categories', [
			        'categories' => $app->parentCategories,
			        'parent_id'=> 0,
			    ]) ?>
			
			
			<?php endif ?>
			
		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('_nav',['app'=>$app])?>
		</div>
	</div>
</div>

<?php

\worstinme\uikit\assets\Nestable::register($this);
\worstinme\uikit\assets\Notify::register($this);

$category_sort_url = Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/category-sort']);
$alias_create_url = Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/alias-create']);

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

$this->registerJs($script,\yii\web\View::POS_END);