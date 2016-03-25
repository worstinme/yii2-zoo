<?php

use yii\helpers\Html;


?>
<li>
	<?= Html::checkbox('categoryIds[]', $checked = false, ['class'=>'category-list', 'label'=>$category->name, 'value' => $category->id]); ?>
	<?php if (count($category->related)): ?>
		<ul>
		<?php foreach ($category->related as $c): ?>
			<?=$this->render('_actions_category',['category'=>$c])?>	
		<?php endforeach ?>
		</ul>
	<?php endif ?>	
</li>