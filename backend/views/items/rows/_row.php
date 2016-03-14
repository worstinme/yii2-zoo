<div class="uk-form-row row<?=$row['class']?' '.$row['class']:''?>">
	<?=!empty($row['name'])?'<h2>'.$row['name'].'</h2>':''?>
	<div class="uk-grid">	
	<?php foreach ($row['items'] as $items): ?>
		<?php if (count($items)): ?>
			<div class="uk-width-1-1">
				<?php foreach ($items as $attribute)
					echo $this->render('/items/_element',['model'=>$model,'attribute'=>$attribute['name']]) ?>
			</div>
		<?php endif; ?>
	<?php endforeach ?>
	</div>
</div>