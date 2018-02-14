<?php

use worstinme\zoo\helpers\ImageHelper;

$key = $key ?? count($model->{$element->attributeName}) + count($model->getTempImages($element->attributeName)) + 1;

if ($image['tmp']) {
    $file = Yii::getAlias($image['source']);
} else {
    $file = Yii::getAlias($element->webroot . $image['source']);
}

?>

<div class="image uk-grid-margin">
    <?php if (is_file($file)): ?>
        <a href="<?= $image['tmp'] ? ImageHelper::thumbnailFileUrl($file, 940) : Yii::getAlias('@web') . $image['source'] ?>"
           class="uk-display-block">
            <?= ImageHelper::thumbnailImg($file, 250, 250) ?>
        </a>
        <i uk-icon="icon: trash" data-remove-image="<?= $image['source'] ?>"></i>
        <i uk-icon="icon: info" data-edit-caption="<?= $image['caption'] ?>"></i>
        <i uk-icon="icon: commenting" data-edit-alt="<?= $image['alt'] ?>"></i>
        <?= \yii\helpers\Html::activeHiddenInput($model, $element->attributeName . "[" . $key . "][source]", ['value' => $image['source']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $element->attributeName . "[" . $key . "][alt]", ['class' => 'alt', 'value' => $image['alt']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $element->attributeName . "[" . $key . "][caption]", ['class' => 'caption', 'value' => $image['caption']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $element->attributeName . "[" . $key . "][tmp]", ['value' => $image['tmp']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $element->attributeName . "[" . $key . "][sort]", ['value' => $key]) ?>
    <?php else: ?>
        <span class="uk-text-danger"><?= Yii::t('zoo', 'FILE_NOT_FOUND') ?></span>
    <?php endif ?>
</div>