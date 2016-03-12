<?php

use yii\helpers\Html; 

?>

<?php if (isset($params['asUrl']) && $params['asUrl'] == 1): ?>

	<h2><?=Html::a($model->name, $model->url,['data-pjax'=>0])?></h2>
	
<?php else: ?>

	<h1><?=$model->name;?></h1>
	
<?php endif ?>