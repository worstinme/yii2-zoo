<?php

$value = implode(", ",$model->{$attribute});

?>

<?php if (!empty($value)): ?>
<span><?=$model->getAttributeLabel($attribute)?>:</span> <?=$value?>
<?php endif ?>