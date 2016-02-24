<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,'alias'), 5);

?>

<?= Html::activeLabel($model, 'alias',['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
    <?= Html::activeInput('text', $model, 'alias',['class'=>'uk-width-1-1']); ?>
    <div class="uk-form-help-block uk-text-danger"></div>
</div>
