<?php

use yii\helpers\Html;

?>


<?= Html::activeInput('text', $model, $element->attributeName, ['class'=>'uk-input']); ?>

<?= $form->field($model, 'element_alias')->element()->label(false); ?>