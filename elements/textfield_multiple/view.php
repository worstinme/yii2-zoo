<?php

$value = implode(", ",$model->{$attribute});

?>

<?php if (!empty($value)): ?>
<span class="label"><?=$model->getAttributeLabel($attribute)?>:</span> <?=$value?>
<?php endif ?>