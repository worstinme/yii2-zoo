<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model, $element->attributeName);

$v = $model->{$element->attributeName};

$v[] = ['mo' => 0, 'tu' => 0, 'we' => 0, 'th' => 0, 'fr' => 0, 'sa' => 0, 'su' => 0, 'start_at' => '0000', 'finish_at' => 2400];

?>

<?php foreach ($v as $key => $value): ?>
  <p>
  <div class="uk-button-group">
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][mo]", ['label' => 'Mo']) ?></label>
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][tu]", ['label' => 'Tu']) ?></label>
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][we]", ['label' => 'We']) ?></label>
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][th]", ['label' => 'Th']) ?></label>
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][fr]", ['label' => 'Fr']) ?></label>
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][sa]", ['label' => 'Sa']) ?></label>
    <label class="uk-button"><?= Html::activeCheckbox($model, $element->attributeName . "[" . $key . "][su]", ['label' => 'Su']) ?></label>
  </div>
    <?= \yii\widgets\MaskedInput::widget([
        'model' => $model,
        'attribute' => $element->attributeName . "[" . $key . "][start_at]",
        'mask' => '99:99',
        'options' => ['size'=>4],
    ]) ?>
    <?= \yii\widgets\MaskedInput::widget([
        'model' => $model,
        'attribute' => $element->attributeName . "[" . $key . "][finish_at]",
        'mask' => '99:99',
        'options' => ['size'=>4],
    ]) ?>
    <?php if ($key==0) : ?>
    <a id="items-schedule" class="uk-button" onclick="$(this).trigger('change')">add</a>
    <?php elseif($key<count($model->{$element->attributeName})-1): ?>
    <a class="uk-button" onclick="$(this).parents('.schedule-row').remove()">remove</a>
    <?php endif; ?>
  </p>
<?php endforeach ?>
