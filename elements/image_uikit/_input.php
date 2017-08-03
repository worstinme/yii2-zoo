<?php

use worstinme\zoo\helpers\ImageHelper;

$key = $key??count($model->{$attribute}) + count($model->getTempImages($attribute)) + 1;

$file = Yii::getAlias($image['tmp'] ? '@app' : '@webroot') . $image['source'];

?>

<div class="image uk-grid-margin">
    <?php if (is_file($file)): ?>
        <a href="<?= $image['tmp'] ? ImageHelper::thumbnailFileUrl($file, 940) : $image['source'] ?>" class="uk-display-block" data-uk-lightbox>
            <?= ImageHelper::thumbnailImg($file, 250, 250) ?>
        </a>
        <a class="uk-icon-trash" data-remove-image="<?= $image['source'] ?>"></a>
        <a class="uk-icon-edit" data-edit-caption="<?= $image['caption'] ?>"></a>
        <a class="uk-icon-font" data-edit-alt="<?= $image['alt'] ?>"></a>
        <a class="uk-icon-arrows" data-sort></a>
        <?= \yii\helpers\Html::activeHiddenInput($model, $attribute . "[" . $key . "][source]", ['value' => $image['source']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $attribute . "[" . $key . "][alt]", ['class' => 'alt', 'value' => $image['alt']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $attribute . "[" . $key . "][caption]", ['class' => 'caption', 'value' => $image['caption']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $attribute . "[" . $key . "][tmp]", ['value' => $image['tmp']]) ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, $attribute . "[" . $key . "][sort]", ['value' => $key]) ?>
    <?php else: ?>
        <span class="uk-text-danger"><?=Yii::t('zoo/image_uikit','FILE_NOT_FOUND')?></span>
    <?php endif ?>
</div>