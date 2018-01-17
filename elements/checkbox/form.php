<?php

use yii\helpers\Html;


?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
    <?= Html::activeCheckbox($model, $attribute,['class'=>'uk-width-1-1','label'=>$element->checkboxLabel??$model->getAttributeLabel($attribute)]); ?>
    <div class="uk-form-help-block uk-text-danger"></div>
</div>
