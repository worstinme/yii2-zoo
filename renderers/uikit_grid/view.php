<?php

use yii\helpers\Html;



foreach ($rows as $row) {

	$elements = [];

	$render_row = false;

	foreach ($row['items'] as $item) {
		if (!empty($item['element']) && !empty($items[$item['element']])) {
			$render_row  = true;
			break;
		}
	}

	if ($render_row) {
		
		$class = !empty($row['params']) && !empty($row['params']['column'])?'uk-grid uk-grid-width-medium-1-'.$row['params']['column']:'row';

		foreach ($row['items'] as $item) {
			
			if (!empty($item['element']) && !empty($items[$item['element']])) { ?>

			<div class="element"><?=$items[$item['element']]?></div>
				
			<? }

		}

	}

}