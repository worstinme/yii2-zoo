<div class="uk-form-row row<?=$class?' '.$class:''?>">
	<h2><?=$name?></h2>
	<div class="uk-grid uk-grid-small">
		<?php foreach ($items as $key => $value): ?>
			<div class="uk-width-medium-1-2 uk-grid-margin">
				<?php foreach ($value as $key => $v): ?>
					<?=$v?>
				<?php endforeach ?>
			</div>
		<?php endforeach ?>
	</div>
</div>