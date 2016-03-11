<?php

use worstinme\zoo\elements\color\HtmlHelper;

$colors = [
	'красный',
	'белый',
];

print_r($searchModel->color);

?>

<label class="f-label">Цвет</label>

<?= HtmlHelper::activeCheckboxList($searchModel, 'color', $colors ) ?>