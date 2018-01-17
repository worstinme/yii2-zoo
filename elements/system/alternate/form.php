<?php

use yii\helpers\Html;use yii\helpers\Url;

$input_id = Html::getInputId($model, $element->attributeName);

\worstinme\zoo\backend\assets\Select2Asset::register($this);

?>

<?= Html::activeDropDownList($model, $element->attributeName,$element->getItems($model),['multiple'=>true])?>

<?php

$callback_url = Url::to(['callback/' . $element->type, 'element' => $element->name, 'app' => $model->app_id]);

$script = <<<JS
    $('#$input_id').select2({width:'100%'});
    $(document).on("change",".element_lang :input", function(e){
        console.log('update alternate items');
        $.post("$callback_url",{lang:$(this).val()}).then(function (data) {
            $('#$input_id').removeClass('select2-offscreen').select2('destroy').empty().select2({width:'100%', data:data});
        });
    });
JS;

$this->registerJs($script, $this::POS_READY)?>


