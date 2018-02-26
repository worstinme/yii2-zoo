<?php

/* @var $this yii\web\View */

use worstinme\zoo\helpers\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$input_id = Html::getInputId($model, $element->attributeName);

$images = array_merge(is_array($model->{$element->attributeName}) ? $model->{$element->attributeName} : [], $model->getTempImages($element->attributeName));

\worstinme\zoo\elements\image_uikit\Asset::register($this);


?>
<?=Html::activeHiddenInput($model,$element->attributeName,['value'=>''])?>
<div class="uploaded-images images-<?= $element->id ?> uk-grid uk-grid-match uk-grid-small uk-child-width-1-2 uk-child-width-1-5@m"
     uk-sortable  uk-lightbox>
    <?php if (count($images)): ?>
        <?php foreach ($images as $key => $image): ?>
            <?= $this->render('_input', [
                'image' => $image,
                'model' => $model,
                'element'=>$element,
                'key' => $key,
            ]) ?>
        <?php endforeach ?>
    <?php endif ?>
</div>

<div class="js-upload-<?= $element->id ?> uk-placeholder uk-text-center uk-flex uk-flex-middle uk-flex-center">
    <div>
        <span uk-icon="icon: cloud-upload"></span><br>
        <?= Yii::t('zoo', 'UPLOAD_FILE') ?>
        <br>
        <div uk-form-custom>
            <span class="uk-link"><?= Yii::t('zoo', 'SELECT_FILE') ?></span>
            <input class="upload-select-<?= $element->id ?>" type="file" multiple>
        </div>
        <progress id="js-progressbar-<?= $element->id ?>" class="uk-progress" value="0" max="100" hidden></progress>
    </div>
</div>

<?php


$csrf = Yii::$app->request->csrfParam;
$upload_url = Url::to(['/zoo/callback/' . $element->type, 'element' => $element->attributeName, 'app' => $model->app_id, 'model_id' => $model->id, 'act' => 'upload']);
$remove_url = Url::to(['/zoo/callback/' . $element->type, 'element' => $element->attributeName, 'app' => $model->app_id, 'model_id' => $model->id, 'act' => 'remove']);
$script = <<<JS

var bar = document.getElementById('js-progressbar-$element->id');

UIkit.upload('.js-upload-$element->id', {
    url: '$upload_url',
    multiple: true,
    params: {
        '$csrf':yii.getCsrfToken(),
    },
    name: "file",
    'data-type': 'json',
    allow : '*.(jpg|jpeg|gif|png)',
    complete: function (response) {
        data = JSON.parse(response.responseText);
        console.log(data);
        if (data.code != undefined && data.code == 200) {
            $('.images-$element->id').append(data.image);
        } else {
            UIkit.notification({
                message: data.message,
                status: 'danger',
                pos: 'top-center',
                timeout: 5000
            });
        }
    },
    loadStart: function (e) {
        bar.removeAttribute('hidden');
        bar.max = e.total;
        bar.value = e.loaded;
    },
    progress: function (e) {
        bar.max = e.total;
        bar.value = e.loaded;
    },
    loadEnd: function (e) {
        bar.max = e.total;
        bar.value = e.loaded;
    },
    completeAll: function (response) {
        setTimeout(function () {
            bar.setAttribute('hidden', 'hidden');
        }, 1000);
    }
});

$(document).on("click",".images-$element->id [data-remove-image]", function(e){
    e.preventDefault();
    var item = $(this).parents('.image');
    console.log($(this).data("remove-image"));
    UIkit.modal.confirm('UIkit confirm!').then(function() {
       /// $.post('$remove_url',{ 'image': $(this).data("remove-image"), '$csrf':yii.getCsrfToken()}, function(response){
///
      ///  });
        item.remove();
    }, function () {
        console.log('Rejected.')
    });
});

$(document).on("click",".images-$element->id [data-edit-alt]", function(e){
    e.preventDefault();
    var item = $(this).parents('.image');
    console.log(item);
    UIkit.modal.prompt("Alt:", item.find(".alt").val()).then(function(newvalue){
        item.find(".alt").val(newvalue);
    });
});

$(document).on("click",".images-$element->id [data-edit-caption]", function(e){
    e.preventDefault();
    var item = $(this).parents('.image');
    console.log(item);
    UIkit.modal.prompt("Caption:", item.find(".caption").val()).then(function(newvalue){
        item.find(".caption").val(newvalue);
    });
});

JS;

if (Yii::$app->request->isAjax) {
    echo '<script>' . $script . '</script>';
} else {
    $this->registerJs($script, $this::POS_READY);
}
