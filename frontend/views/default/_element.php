<div class="element-<?=$attribute?>">
	<?= $this->render('@worstinme/zoo/elements/'.$page->elements[$attribute]['type'].'/_view.php',['model'=>$page,'attribute'=>$attribute,'params'=>$params]); ?>
</div>