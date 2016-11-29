<?php

use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\uikit\ActiveForm;

$rows=$model->getTemplateRows('form');

?>

<div class="uk-panel uk-panel-box">

<?php $form = ActiveForm::begin(['id'=>'form', 'layout'=>'stacked', 'enableClientValidation' => false]); 

if (count($model->errors)) {
    echo $form->errorSummary($model);
}

echo \worstinme\zoo\helpers\TemplateHelper::render($model,'form');

?>


<hr>

<div class="uk-grid">

    <div class="uk-width-medium-2-3">

    <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

    <?= $form->field($model, 'metaDescription')->textarea(['rows' => 2,'class'=>'uk-width-1-1']) ?>

    <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

    </div>

    <div class="uk-width-medium-1-3">

        <?= $form->field($model, 'state')->dropDownList([
            $model::STATE_HIDDEN=>'Hidden',
            $model::STATE_ACTIVE=>'Active',
        ], ['option' => 'value']); ?>

        <?= $form->field($model, 'flag')->checkbox(['option' => 'value'])->label('&nbsp;'); ?>
 
    </div>

</div>

<div class="uk-form-row uk-margin-top">
    <?=Html::submitButton('Сохранить',['name'=>'save','value'=>'continue','class'=>'uk-button uk-button-success'])?>
    <?=Html::submitButton('Сохранить и закрыть',['name'=>'save','value'=>'close','class'=>'uk-button uk-button-primary'])?>
</div>

<?php ActiveForm::end();  ?>

</div>

<?php

$refresh_el = ['.category-select'];

foreach ($model->elements as $element) {
    if ($element->refresh) {
        $refresh_el[] = "#".Html::getInputId($model,$element->name);
    }
    if ($element->related) {
        $refresh_el[] = "#".Html::getInputId($model,$element->related);
    }
}
     
$refresh_el = implode(",",$refresh_el);

$renderedElements = Json::encode($model->renderedElements);

// echo Html::hiddenInput('renderedElements', $renderedElements, ['option' => 'value']);

$js = <<<JS
var form = $("#form");
var renderedElements = $renderedElements;

form.on("change",'$refresh_el', function() {
    if (!form.hasClass('update')) {
        form.addClass('update');
        $.post(location.href, form.serialize() + '&' + $.param({reload:'true','renderedElements':renderedElements}),
            function(data){
                for (var i in data.removeElements) {
                    form.find(".element-"+data.removeElements[i]).html("");
                    form.yiiActiveForm('remove', 'element-'+data.removeElements[i]);
                }
                for (var i in data.renderElements) {
                    form.find(".element-"+i).removeClass('uk-hidden').html(data.renderElements[i]);
                }                
                renderedElements = data.renderedElements;
                console.log(data);
                form.removeClass('update');
            }
        ); 
    }        
})
JS;

$this->registerJs($js, $this::POS_READY); ?>

</div>