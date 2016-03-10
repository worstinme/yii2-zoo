<?php

use yii\helpers\Html;

$colors = [
	'Красный',
	'Белый',
];

?>
<label>Цвет</label>

<?php foreach ($colors as $color): ?>
	<?= Html::activeCheckbox($searchModel, 'color[]', ['value' => $color,'label'=>false]) ?>
<?php endforeach ?>