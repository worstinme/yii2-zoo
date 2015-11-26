<div class="uk-form-row row<?=$class?' '.$class:''?>">
	<h2><?=$name?></h2>
	<div class="uk-grid">
		<?php foreach ($items as $key => $value): ?>
			<div class="uk-width-1-1">
				<?php foreach ($value as $key => $v): ?>
					<?=$v?>
				<?php endforeach ?>
			</div>
		<?php endforeach ?>
	</div>
</div>