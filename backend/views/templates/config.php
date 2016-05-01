<?php 

use yii\helpers\Html;

$app = $this->context->app;
$positions = !empty($app->getTemplatesConfig($name)['positions']) ? $app->getTemplatesConfig($name)['positions'] : [];
$rows = !empty($template['rows']) ? $template['rows'] : []; ?>

<div class="rows" data-template='{"name": "<?=$name?>"}'>

	<?php foreach ($positions as $key=>$position): ?>
	<div class="fieldset uk-form uk-panel uk-panel-box" data-row='<?=$position?>'>
		<div class="header"><?=$position?>	</div>
		<ul class="uk-nestable" data-uk-nestable="{group:'elements',maxDepth:1,handleClass:'uk-nestable-handle'}">
		<?php if (!empty($rows[$position]) && !empty($rows[$position]['items']) && is_array($rows[$position]['items'])): ?>
		<?php foreach ($rows[$position]['items'] as $item) {
			if (!empty($item['element']) && isset($elements[$item['element']])) {
				echo $this->render('_element',[
					'element'=>$elements[$item['element']],
					'params'=>!empty($item['params'])?$item['params']:''
				]);
			}
		} ?>
		<?php endif ?></ul>
	</div>
	<?php endforeach ?>

	<?php if (!count($positions)): ?>

		<?php foreach ($rows as $row): ?>
			<div class="fieldset default uk-form uk-panel uk-panel-box" data-row>
				<div class="header">	</div>
				<ul class="uk-nestable" data-uk-nestable="{group:'elements',maxDepth:2,handleClass:'uk-nestable-handle'}">
				<?php if (!empty($row) && !empty($row['items']) && is_array($row['items'])): ?>
				<?php foreach ($row['items'] as $item) {
					if (!empty($item['element']) && isset($elements[$item['element']])) {
						echo $this->render('_element',[
							'element'=>$elements[$item['element']],
							'params'=>!empty($item['params'])?$item['params']:''
						]);
					}
				} ?>
				<?php endif ?></ul>
			</div>
		<?php endforeach ?>
			<div class="fieldset default uk-form uk-panel uk-panel-box" data-row>
				<div class="header"></div>
				<ul class="uk-nestable"></ul>
			</div>
	<?php endif ?>

</div>	

<a href="#" add-save-group class="uk-button uk-button-success uk-margin-top">Сохранить</a>
<?php if (!count($positions)): ?>
<a href="#" add-new-group class="uk-button uk-button-primary uk-margin-top">Добавить строку</a>
<?php endif ?>

<?php

$script = <<< JS
(function($){

	var elements = UIkit.nestable($('#elements,.rows .uk-nestable'), {group:'elements',maxDepth:2,handleClass:'uk-nestable-handle'});

	$("[add-new-group]").on("click",function(){
		var row = $('.fieldset.default:last-child').clone();
		$(".rows").append(row);
		row.find('.uk-nestable').html("");
		var nestable = UIkit.nestable(row.find('.uk-nestable'), {group:'elements',maxDepth:2,handleClass:'uk-nestable-handle'});
	});

	elements.on('change.uk.nestable',function(event,it,item,action){
		$("#elements [data-element="+item.data("element")+"]").remove();
		$("#elements").append(item.clone());
	});

	$("[add-save-group]").on("click",function(){

		var data = $(".rows").data("template");

		data.rows = [];

		$(".rows").children('[data-row]').each(function(index) {
			
			var row = {"params":$(this).data("row"),"items":[]};

			$(this).find("[data-element]").each(function(indx) {

				var item = {'element':$(this).data('element'),'params':$(this).find('form').serializeObject()};

				row['items'].push(item);

			});

			data.rows[data.rows.length] = row;

		});

		console.log(data);

		$.post( "$url",data, function( resp ) {
		 	UIkit.notify(resp,100000);
		});
	});

	$.fn.serializeObject = function()
	{
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};

})(jQuery);

JS;

$this->registerJs($script,\yii\web\View::POS_READY);
