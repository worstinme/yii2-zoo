<?php

/* @var $this yii\web\View */

use worstinme\zoo\helpers\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;

worstinme\uikit\assets\Upload::register($this);
worstinme\uikit\assets\Notify::register($this);
worstinme\uikit\assets\Lightbox::register($this);
worstinme\uikit\assets\Sortable::register($this);

$input_id = Html::getInputId($model, $attribute);

$images = array_merge(is_array($model->{$attribute}) ? $model->{$attribute} : [], $model->getTempImages($attribute));

\worstinme\zoo\elements\image_uikit\Asset::register($this);

?>

<?php if (!empty($element->adminHint)): ?>
    <i class="uk-icon-info-circle uk-float-right" data-uk-toggle="{target:'.hint-<?= $input_id ?>'}"></i>
    <?= Html::activeLabel($model, $attribute, ['class' => 'uk-form-label']); ?>
    <p class="hint-<?= $input_id ?> uk-hidden">
        <?= $element->adminHint ?>
    </p>
<?php else: ?>
    <?= Html::activeLabel($model, $attribute, ['class' => 'uk-form-label']); ?>
<?php endif ?>
    <div class="uk-from-controls">
        <?=Html::activeHiddenInput($model,$attribute,['value'=>'empty'])?>
        <div class="uploaded-images images-<?= $element->id ?> uk-grid uk-grid-match uk-grid-small uk-grid-width-1-2 uk-grid-width-medium-1-5"
             data-uk-observe data-uk-sortable="{handleClass:'uk-icon-arrows'}">
            <?php if (count($images)): ?>
                <?php foreach ($images as $key => $image): ?>
                    <?= $this->render('_input', [
                        'image' => $image,
                        'model' => $model,
                        'attribute' => $attribute,
                        'key' => $key,
                    ]) ?>
                <?php endforeach ?>
            <?php endif ?>
        </div>
        <div class="upload-drop-<?= $element->id ?> uk-placeholder uk-text-center uk-flex uk-flex-middle uk-flex-center">
            <div><i class="uk-icon-cloud-upload uk-icon-medium uk-text-muted uk-margin-small-right"></i><br>
                <?= Yii::t('zoo/image_uikit', 'UPLOAD_FILE') ?>
                <br>
                <a class="uk-form-file">
                    <?= Yii::t('zoo/image_uikit', 'SELECT_FILE') ?>
                    <input class="upload-select-<?= $element->id ?>" type="file" multiple="multiple">
                </a>
                <div class="progressbar-<?= $element->id ?> uk-progress uk-hidden">
                    <div class="uk-progress-bar" style="width: 0%;">0%</div>
                </div>
            </div>
        </div>
        <div class="uk-danger"></div>
    </div>

<?php


$csrf = Yii::$app->request->csrfParam;
$upload_url = Url::to(['/' . Yii::$app->controller->module->id . '/callback/' . $element->type, 'element' => $element->name, 'model_id' => $model->id, 'act' => 'upload']);
$remove_url = Url::to(['/' . Yii::$app->controller->module->id . '/callback/' . $element->type, 'element' => $element->name, 'model_id' => $model->id, 'act' => 'remove']);
$script = <<<JS

var progressbar = $(".progressbar-$element->id"),
    bar = progressbar.find('.uk-progress-bar'),
    settings  = {
        action: '$upload_url', 
        single: 1,
        param: "file",
        filelimit:10,
        params: {
            '$csrf':yii.getCsrfToken(),
        },
        type: 'json',
        allow : '*.(jpg|jpeg|gif|png)', 
        loadstart: function() {
            bar.css("width", "0%").text("0%");
            progressbar.removeClass("uk-hidden");
        },
        progress: function(percent) {
            percent = Math.ceil(percent);
            bar.css("width", percent+"%").text(percent+"%");
        },
        complete: function(response) {
            bar.css("width", "100%").text("100%");
            setTimeout(function(){
               progressbar.addClass("uk-hidden");
            }, 250);
            console.log(response);
            if (response.code !== undefined && response.code == 100) {
                $('.images-$element->id').append(response.image);
            } else {
                UIkit.notify(response.message, {status:'danger'})
            }
        }
    };

var select = UIkit.uploadSelect($(".upload-select-$element->id"), settings),
    drop   = UIkit.uploadDrop($(".upload-drop-$element->id"), settings);

$(document).on("click",".images-$element->id [data-remove-image]", function(e){
    e.preventDefault();
    var item = $(this).parents('.image');
    UIkit.modal.confirm("Are you sure?", function(){
        item.remove();
    });
});

$(document).on("click",".images-$element->id [data-edit-alt]", function(e){
    e.preventDefault();
    var item = $(this).parents('.image');
    console.log(item);
    UIkit.modal.prompt("Alt:", item.find(".alt").val(), function(newvalue){
        item.find(".alt").val(newvalue);
    });
});

$(document).on("click",".images-$element->id [data-edit-caption]", function(e){
    e.preventDefault();
    var item = $(this).parents('.image');
    console.log(item);
    UIkit.modal.prompt("Caption:", item.find(".caption").val(), function(newvalue){
        item.find(".caption").val(newvalue);
    });
});

JS;

if (Yii::$app->request->isAjax) {
    echo '<script>' . $script . '</script>';
} else {
    $this->registerJs($script, $this::POS_READY);
}
