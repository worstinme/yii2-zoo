<?php

use yii\helpers\Html;

/** @var $element \worstinme\zoo\elements\checkbox\Element */

?>

<?php if ($element->multiple) : ?>

    <?= Html::activeCheckboxList($model, $element->attributeName, $element->variants) ?>
<?php else: ?>
    <?= Html::activeCheckbox($model, $element->attributeName, ['class' => 'uk-checkbox', 'label' => !empty($element->checkboxLabel) ? $element->checkboxLabel : $model->getAttributeLabel($element->attributeName)]); ?>
<?php endif; ?>