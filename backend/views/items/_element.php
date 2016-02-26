<div class="element-<?=$attribute?>">
<?php if (in_array($attribute,$model->renderedElements)): ?>
	<?= $this->render('@worstinme/zoo/elements/'.$model->elements[$attribute]['type'].'/_form.php',['model'=>$model,'attribute'=>$attribute,]); ?>
<?php endif ?>
</div>