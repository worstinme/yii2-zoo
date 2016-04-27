<?php

use yii\helpers\Html;

$rows = $model->getTemplateRows('teaser');

$class = !empty($row['params']) && !empty($row['params']['column'])?'uk-grid uk-grid-width-medium-1-'.$row['params']['column']:'';

?>
<div class="item item-teaser <?=$class?>"> 
<?php foreach ($rows as $row) {

		foreach ($row['items'] as $item) {
			
			if (!empty($item['element'])) {  ?>

			<div class="element element-<?=$item['element']?>">
				
			<?= $this->render('@worstinme/zoo/elements/'.$model->elements[$item['element']]['type'].'/_view.php',[
	            'model'=>$model,
	            'attribute'=>$item['element'],
	            'params'=>!empty($item['params'])?$item['params']:[],
	        ]);?>		
				
			</div>
				
			<?php }

		}

} ?>
</div>