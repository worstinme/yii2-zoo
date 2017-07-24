<?php

use worstinme\zoo\helpers\ImageHelper;

$key = $key??count($model->{$attribute}) + count($model->getTempImages($attribute)) + 1;

?>

<div class="image uk-grid-margin">
    <a href="<?= ImageHelper::thumbnailFileUrl(($image['tmp']?'@app':'@webroot'). $image['source'], 940, 640)?>" class="uk-display-block" data-uk-lightbox>
        <?=ImageHelper::thumbnailImg(($image['tmp']?'@app':'@webroot'). $image['source'], 250, 250)?>
    </a>
    <a class="uk-icon-trash" data-remove-image="<?=$image['source']?>"></a>
    <a class="uk-icon-edit" data-edit-caption="<?=$image['caption']?>"></a>
    <a class="uk-icon-font" data-edit-alt="<?=$image['alt']?>"></a>
    <a class="uk-icon-arrows" data-sort></a>
    <?=\yii\helpers\Html::activeHiddenInput($model,$attribute."[".$key."][source]",['value'=>$image['source']])?>
    <?=\yii\helpers\Html::activeHiddenInput($model,$attribute."[".$key."][alt]",['class'=>'alt','value'=>$image['alt']])?>
    <?=\yii\helpers\Html::activeHiddenInput($model,$attribute."[".$key."][caption]",['class'=>'caption','value'=>$image['caption']])?>
    <?=\yii\helpers\Html::activeHiddenInput($model,$attribute."[".$key."][tmp]",['value'=>$image['tmp']])?>
    <?=\yii\helpers\Html::activeHiddenInput($model,$attribute."[".$key."][sort]",['value'=>$key])?>
</div>