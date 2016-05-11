<?php

use yii\helpers\Html;

$rows = $model->getTemplateRows('teaser');

$class = !empty($row['params']) && !empty($row['params']['column'])?'uk-grid uk-grid-width-medium-1-'.$row['params']['column']:'';

?>
<div class="item item-teaser <?=$class?>"> 
	<?=\worstinme\zoo\helpers\TemplateHelper::render($model,'teaser')?>
</div>