<?php

use yii\helpers\Html;

?>

<ul>
	<?php foreach ($items as $key => $item): ?>
	<li>
		<?=\worstinme\zoo\helpers\TemplateHelper::render($item,'list-item')?>
	</li>
	<?php endforeach ?>
</ul>
