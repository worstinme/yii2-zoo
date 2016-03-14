
<?=!empty($row['name'])?'<h2>'.$row['name'].'</h2>':''?>
<?php foreach ($row['items'] as $items): ?>
	<?php if (count($items)): ?>
		<div class="row<?=$row['class']?' '.$row['class']:''?>">
			<?php foreach ($items as $attribute)
				echo $this->render('/default/_element',[
						'model'=>$model,
						'attribute'=>$attribute['name'],
						'params'=>isset($attribute['params']) ? $attribute['params'] : null,
					]) ?>
		</div>
	<?php endif; ?>
<?php endforeach ?>