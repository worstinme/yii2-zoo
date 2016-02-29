<div class="element-<?=$attribute?>">
	<?= $this->render('@worstinme/zoo/elements/'.$model->elements[$attribute]['type'].'/_view.php',['model'=>$model,'attribute'=>$attribute,'params'=>$params]); ?>
</div>