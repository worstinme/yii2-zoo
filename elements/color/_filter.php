<?php

use worstinme\zoo\elements\color\HtmlHelper;

$colors = [
	'красный',
	'белый',
];

?>

<label class="f-label">Цвет</label>

<?= HtmlHelper::activeCheckboxList($searchModel, 'color', $colors,['class'=>'color-filter']) ?>