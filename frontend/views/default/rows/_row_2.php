<div class="uk-form-row row<?=$class?' '.$class:''?>">
	<?=!empty($row['name'])?'<h2>'.$row['name'].'</h2>':''?>
	<div class="uk-grid">	
	<?php foreach ($row['items'] as $items): ?>
		<?php if (count($items)): ?>
			<div class="uk-width-1-2">
				<?php foreach ($items as $attribute)
					echo $this->render('/default/_element',['page'=>$page,'attribute'=>$attribute['name']]) ?>
			</div>
		<?php endif ?>
	<?php endforeach ?>
	</div>
</div>