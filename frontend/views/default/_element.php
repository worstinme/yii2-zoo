<?php

$type = $page->elements[$attribute]['type'];

?>
<div class="element-<?=$attribute?>">
	<?= $this->render('@worstinme/zoo/elements/'.$type.'/_view.php',['model'=>$page,'attribute'=>$attribute,]); ?>
</div>