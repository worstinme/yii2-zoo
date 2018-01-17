<?php


?>

<select name="type">
	<?php foreach (\worstinme\zoo\helpers\TemplateHelper::types() as $value): ?>
		<?php if (!empty($row['type']) && $row['type'] == $value): ?>
			<option selected value="<?=$value?>"><?=$value?></option>

		<?php else: ?>
			<option value="<?=$value?>"><?=$value?></option>
		<?php endif ?>
	<?php endforeach ?>
</select>