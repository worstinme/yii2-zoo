<?php

$branding = isset($params['branding']) ? (int)$params['branding'] : null;

?>

<?=$model->price?> <i class="uk-icon-rub"></i>

<?php if ($branding ): ?>
	<a href="/branding/" class="uk-float-right uk-button uk-button-inverse">Стоимость нанесения логотипа</a>
<?php endif ?>