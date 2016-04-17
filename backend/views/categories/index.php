<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('backend','Категории');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?=$this->render('/_nav',['app'=>$app])?>

<?php $form = ActiveForm::begin(['action'=>['update','app'=>$app->id],
		'id' => 'login-form','layout'=>'stacked',
		'field_width'=>'large']); ?>
                    
    <div class="uk-grid uk-grid-small">

    <div class="uk-width-3-10">
    <?= $form->field($model, 'name')->textInput(['placeholder'=>$model->getAttributeLabel('name'),"data-aliascreate"=>"#categories-alias"])->label(false)  ?>
    </div>
    <div class="uk-width-3-10">
    <?= $form->field($model, 'parent_id')
	    	->dropDownList($app->catlist,['prompt'=>Yii::t('backend','Корневая категория')])->label(false); ?> 
	</div>
    <div class="uk-width-2-10">
    <?= $form->field($model, 'alias')->textInput(['placeholder'=>$model->getAttributeLabel('alias')])->label(false)  ?>
    </div>   
    <div class="uk-width-2-10">          
    <div class="uk-form-row">
        <?= Html::submitButton(Yii::t('backend','Создать'),['class'=>'uk-button uk-button-success uk-width-1-1']) ?>
    </div>
    </div>
    </div>
<?php ActiveForm::end(); ?>

<hr>

<?php if (count(Yii::$app->controller->app->parentCategories)): ?>

	<?= $this->render('_categories', [
        'categories' => Yii::$app->controller->app->parentCategories,
        'parent_id'=> 0,
    ]) ?>

<?php endif ?>

<?php

\worstinme\uikit\assets\Nestable::register($this);
\worstinme\uikit\assets\Notify::register($this);

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

$this->registerJs($script,\yii\web\View::POS_END);