<?php 

use yii\helpers\Html;


?>

<ul class="row-list">
	<li>
		<div class="fieldset uk-form uk-panel uk-hidden" data-row='{"column": 1}'>
			<ul class="uk-nestable uk-grid-small uk-grid uk-grid-width-1-1"></ul>
		</div>
		<a href="#" add-new-group class="uk-button"><i class="uk-icon-plus"></i> 1 Колонка</a>
	</li>
	<li>
		<div class="fieldset uk-form uk-panel uk-hidden" data-row='{"column": 2}'>
			<ul class="uk-nestable uk-grid-small uk-grid uk-grid-width-1-2"></ul>
		</div>
		<a href="#" add-new-group class="uk-button"><i class="uk-icon-plus"></i> 2 Колонки</a>
	</li>
	<li>
		<div class="fieldset uk-form uk-panel uk-hidden" data-row='{"column": 3}'>
			<ul class="uk-nestable uk-grid-small uk-grid uk-grid-width-1-3"></ul>
		</div>
		<a href="#" add-new-group class="uk-button"><i class="uk-icon-plus"></i> 3 Колонки</a>
	</li>
</ul>

<hr>


<div class="rows" data-template='{"name": "<?=$name?>", "renderer": "uikit_grid", "rendererViewPath": "@worstinme/zoo/renderers/uikit_grid/view"}'>

<?php if (!empty($template['rows']) && is_array($template['rows'])) {


	foreach ($template['rows'] as $row) { 

		$column = !empty($row['params']) && !empty($row['params']['column']) ? $row['params']['column'] : 1; ?>

		<div class="fieldset uk-form uk-panel" data-row='{"column": <?=$column?>}'>
			<ul class="uk-nestable uk-grid-small uk-grid uk-grid-width-1-<?=$column?>">
			
			<?php if (!empty($row['items']) && is_array($row['items'])) {

				foreach ($row['items'] as $item) {

					$element = !empty($item['element']) && isset($elements[$item['element']]) ?
						$elements[$item['element']] : null;

					if ($element !== null) {
						echo $this->render('@worstinme/zoo/backend/views/templates/_element',['element'=>$element,'params'=>!empty($item['params'])?$item['params']:'']);
					}
					else { ?>

					<li class="uk-nestable-item">
					    <div class="uk-nestable-panel">
					        <i class="nestable-handle uk-icon uk-icon-bars uk-margin-small-right"></i> Элемент не найден
					    </div>
					</li>

					<?php }
					
				}

			} ?>

			</ul>
		</div>
	
	<? } 

} ?>

</div>	

<hr>

<a href="#" add-save-group class="uk-button uk-button-success">Сохранить</a>


<?php

$script = <<< JS

(function($){

	var elements = UIkit.nestable($('#elements,.rows .uk-nestable'), {group:'elements',maxDepth:1});

	elements.on('change.uk.nestable',function(event,it,item,action){
		$("#elements [data-element="+item.data("element")+"]").remove();
		$("#elements").append(item.clone());
	});

	$("[add-new-group]").on("click",function(){
		var row = $(this).prev('div').clone().removeClass('uk-hidden');
		$(".rows").append(row);
		var nestable = UIkit.nestable(row.find('.uk-nestable'), {group:'elements',maxDepth:1});
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
