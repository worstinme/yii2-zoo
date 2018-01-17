<?php

use yii\helpers\Html;

$input_id = Html::getInputId($model, $attribute);

$v = $model->{$attribute};

$v[] = ['mo' => 0, 'tu' => 0, 'we' => 0, 'th' => 0, 'fr' => 0, 'sa' => 0, 'su' => 0, 'start_at' => '0000', 'finish_at' => 2400];

?>

<?php if (!empty($element->admin_hint)): ?>
    <i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?= $input_id ?>'}"></i>
    <?= Html::activeLabel($model, $attribute, ['class' => 'uk-form-label']); ?>
    <p class="hint-<?= $input_id ?> uk-hidden">
        <?= $element->admin_hint ?>
    </p>
<?php else: ?>
    <?= Html::activeLabel($model, $attribute, ['class' => 'uk-form-label']); ?>
<?php endif ?>

<div class="uk-from-controls">
    <?php foreach ($v as $key => $value): ?>
        <p>
                <div class="uk-button-group">
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][mo]", ['label' => 'Mo']) ?></label>
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][tu]", ['label' => 'Tu']) ?></label>
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][we]", ['label' => 'We']) ?></label>
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][th]", ['label' => 'Th']) ?></label>
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][fr]", ['label' => 'Fr']) ?></label>
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][sa]", ['label' => 'Sa']) ?></label>
                    <label class="uk-button"><?= Html::activeCheckbox($model, $attribute . "[" . $key . "][su]", ['label' => 'Su']) ?></label>
                </div>
                <?= \yii\widgets\MaskedInput::widget([
                    'model' => $model,
                    'attribute' => $attribute . "[" . $key . "][start_at]",
                    'mask' => '99:99',
                    'options' => ['size'=>4],
                ]) ?>
                <?= \yii\widgets\MaskedInput::widget([
                    'model' => $model,
                    'attribute' => $attribute . "[" . $key . "][finish_at]",
                    'mask' => '99:99',
                    'options' => ['size'=>4],
                ]) ?>
                <?php if ($key==0) : ?>
                    <a id="items-schedule" class="uk-button" onclick="$(this).trigger('change')"><i class="uk-icon-plus"></i></a>
                <?php elseif($key<count($model->{$attribute})-1): ?>
                    <a class="uk-button" onclick="$(this).parents('.schedule-row').remove()"><i class="uk-icon-times"></i></a>
                <?php endif; ?>
        </p>
    <?php endforeach ?>
    <div class="uk-form-help-block uk-text-danger"></div>
</div>
