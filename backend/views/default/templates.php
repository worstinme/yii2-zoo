<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::t('backend','Шаблоны');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications applications-index">
	<div class="uk-grid">
		<div class="uk-width-medium-8-10">
			<ul class="uk-subnav uk-subnav-line">
			<?php foreach ($templates as $key => $value): ?>
				<?php if ($item !== null): ?>
					<li class="<?=$at != $value?:'uk-active'?>"><?=Html::a($value,['templates','template'=>$value,'app'=>Yii::$app->controller->app->id,'item'=>$item->id])?></li>	
				<?php else: ?>
					<li class="<?=$at != $value?:'uk-active'?>"><?=Html::a($value,['templates','template'=>$value,'app'=>Yii::$app->controller->app->id])?></li>	
				<?php endif ?>
				<?php endforeach ?>
			</ul>
			<hr>
			<div class="uk-grid">
			<?php if ($rows !== null): ?>
				<div class="uk-width-medium-6-10">
					<div id="rows" data-template-name="<?=$at?>">
					<?php if (count($rows)): ?>
						<?php foreach ($rows as $row): ?>
						<div class="fieldset uk-form uk-panel">
							<div class="uk-grid uk-grid-collapse">
								<input type="text" class="uk-width-1-3" name="group" value="<?=$row['name']?>" placeholder="Заголовок группы">
								<input type="text" class="uk-width-1-3" name="class" value="<?=$row['class']?>" placeholder="css class группы">
								<select name="type" class="uk-width-1-3">
									<option<?=$row['type'] == '_row'?' selected="selected"':''?> value="_row">1 колонка</option>
									<option<?=$row['type'] == '_row_2'?' selected="selected"':''?> value="_row_2">2 колонки</option>
									<option<?=$row['type'] == '_row_2_double'?' selected="selected"':''?> value="_row_2_double">2 колонки 66% / 33%</option>
									<option<?=$row['type'] == '_row_2_double_r'?' selected="selected"':''?> value="_row_2_double_r">2 колонки 33% / 66%</option>
									<option<?=$row['type'] == '_row_3'?' selected="selected"':''?> value="_row_3">3 колонки</option>
									<option<?=$row['type'] == '_row_orientir'?' selected="selected"':''?> value="_row_4">_row_orientir</option>
									<option<?=$row['type'] == '_row_combo'?' selected="selected"':''?> value="_row_combo">объединенно</option>
								</select>
							</div>
							<ul class="uk-nestable" data-uk-nestable="{group:'elements',maxDepth:2}">
								<?php foreach ($row['items'] as $items):  ?>
									<? $it = array_shift($items); ?>
									<?php if (array_key_exists($it['name'], $elements)): ?>									
									
									<li class="uk-nestable-item" data-element-id="<?=$it['name']?>">
								        <div class="uk-nestable-panel">
								            <i class="uk-nestable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
								            <?=$elements[$it['name']]->title?>
								            <?=$this->render($elements[$it['name']]->paramsView,['params'=>isset($it['params']) ? $it['params'] : null])?>
								            <? unset($elements[$it['name']]); ?>
								        </div>
								        <?php if (count($items)): ?>
								        <ul class="uk-nestable">
								    	<?php foreach ($items as $value => $pars):  ?>
								    		<?php if (array_key_exists($value, $elements)): ?>	
								    		<li class="uk-nestable-item" data-element-id="<?=$value?>">
								    		<div class="uk-nestable-panel">
									            <i class="uk-nestable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
									            <?=$elements[$value]->title?>
									            <? unset($elements[$value]); ?>
									        </div>
								    		</li>
								    		 <?php endif ?>
								    	<?php endforeach ?>
								    	</ul>
								    	<?php endif ?>
								    </li>

								    <?php endif ?>

								<?php endforeach ?>	
							</ul>
						</div>	
						<?php endforeach ?>
					<?php endif ?>
					</div>
						
					<div class="uk-margin-top uk-margin-left">
						<div class="uk-display-inline-block">
							<div class="fieldset uk-form uk-panel uk-hidden">
								<div class="uk-grid uk-grid-collapse">
									<input type="text" class="uk-width-1-3" name="group" placeholder="Заголовок группы">
									<input type="text" class="uk-width-1-3" name="class" placeholder="css class группы">
									<select name="type" class="uk-width-1-3">
										<option value="_row">1 колонка</option>
										<option value="_row_2">2 колонки</option>
										<option value="_row_2_double">2 колонки 66% / 33%</option>
										<option value="_row_2_double_r">2 колонки 33% / 66%</option>
										<option value="_row_3">3 колонки</option>
										<option value="_row_4">3 колонки</option>
										<option value="_row_combo">объединенно</option>
									</select>
								</div>
								<ul class="uk-nestable"></ul>
							</div>
							<a href="#" add-new-group class="uk-button uk-button-primary">Добавить группу</a>
						</div>
						<div class="uk-display-inline-block">
							<a href="#" add-save-group class="uk-button uk-button-success">Сохранить</a>
						</div>
					</div>
				</div>
				<div class="uk-width-medium-4-10">
					<h3>Доступные поля</h3>
					<?php if (count($elements)): ?>
					<ul id="elements" class="uk-nestable">
					<?php foreach ($elements as $element): ?>
						<li class="uk-nestable-item" data-element-id="<?=$element->name?>">
					        <div class="uk-nestable-panel">
					            <i class="uk-nestable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
					            <?=$element->title?>
					            <?=$this->render($element->paramsView,[$element])?>
					        </div>
					    </li>
					<?php endforeach ?>	
					</ul>
					<?php endif ?>
				</div>
				<?php endif ?>
			</div>
		</div>
		<div class="uk-width-medium-2-10">
			<?=$this->render('/_nav')?>
		</div>
	</div>
</div>

<?php

\worstinme\uikit\assets\Nestable::register($this);
\worstinme\uikit\assets\Notify::register($this);

$template_url = $item !== null  
	? Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/template','app'=>Yii::$app->controller->app->id,'item'=>$item->id]) 
	: Url::toRoute(['/'.Yii::$app->controller->module->id.'/default/template','app'=>Yii::$app->controller->app->id]);

$script = <<< JS

(function($){

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

	var elements = UIkit.nestable($("#elements"), { group:'elements',maxDepth:1 });

	$("[add-new-group]").on("click",function(){
		var row = $(this).prev('div').clone().removeClass('uk-hidden');
		$("#rows").append(row);
		var nestable = UIkit.nestable(row.find('.uk-nestable'), {group:'elements',maxDepth:2});
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
				item.push({'name':$(this).data('element-id'),'params':$(this).find('form').serializeObject()});
				console.log($(this).find('form').serializeObject());
				$(this).find('li.uk-nestable-item').each(function(ind) {
					item.push({'name':$(this).data('element-id'),'params':$(this).find('form').serializeObject()});
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