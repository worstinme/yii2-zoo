<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('admin','Шаблоны');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

$templates = Yii::$app->controller->module->itemTemplates;

?>
<div class="applications applications-index">
	<div class="uk-grid">
		<div class="uk-width-medium-3-5">
			
			<ul class="uk-tab">
			<?php foreach ($templates as $key => $value): ?>
				<li><a href=""><?=Html::a($value,['templates','app'=>$app->id,'template'=>$value])?></a></li>
			<?php endforeach ?>
			</ul>

			<ul id="templates" class="uk-switcher uk-margin">
			<?php foreach ($templates as $key => $value): ?>
				<li data-template="<?=$value?>">
					<?=$value?>
				</li>
			<?php endforeach ?>
			</ul>

		</div>
		<div class="uk-width-medium-1-5">
			<?=$app->fields?>
		</div>
		<div class="uk-width-medium-1-5">
			<?=$this->render('_nav',['app'=>$app])?>
		</div>
	</div>
</div>

<?php

\worstinme\uikit\assets\Nestable::register($this);
\worstinme\uikit\assets\Notify::register($this);

$template_url = Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/template','app'=>$app->id]);

$script = <<< JS

(function($){

	var fields = UIkit.nestable($("#fields"), { group:'fields',maxDepth:1 });

	$("[add-new-group]").on("click",function(){
		var row = $(this).prev('div').clone().removeClass('uk-hidden');
		$("#rows").append(row);
		var nestable = UIkit.nestable(row.find('.uk-nestable'), {group:'fields',maxDepth:2});
	});

	$("[add-save-group]").on("click",function(){
		var data = [];
		var rows = []; 
		$("#rows").children('.fieldset').each(function(index) {
			var row = [];
			row['name'] = $(this).find('input[name="group"]').val();
			row['class'] = $(this).find('input[name="class"]').val();
			row['type'] = $(this).find('select[name="type"]').val();
			row['items'] = [];
			$(this).children(".uk-nestable").children("li.uk-nestable-item").each(function(indx) {
				var item = [];
				item.push($(this).data('field-id'));
				$(this).find('li.uk-nestable-item').each(function(ind) {
					item.push($(this).data('field-id'));
				});
				item = $.extend({}, item);
				row['items'].push(item);
			});
			row = $.extend({}, row);
			rows.push(row);
		});
		rows = $.extend({}, rows);
		data['rows'] = rows;
		data['template'] = $("#rows").data('template-name');
		obj = $.extend({}, data);
		console.log(obj);
		$.post( "$template_url",obj, function( resp ) {
		 	UIkit.notify(resp,100000);
		});
	});

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_END);