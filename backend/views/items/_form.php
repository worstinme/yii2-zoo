<?php

use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\uikit\ActiveForm;

$template = Yii::$app->controller->app->getTemplate('form'); ?>

<div class="uk-panel uk-panel-box">

<?php $form = ActiveForm::begin(['id'=>'form', 'layout'=>'stacked', 'enableClientValidation' => false]);

if (count($template)) {
    foreach ($template as $row) {
        if (count($row['items'])) {
            echo $this->render('rows/'.$row['type'],[
                'row'=>$row,
                'model'=>$model,
            ]);    
        }
    }
}
?>

<hr>


<?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

<?= $form->field($model, 'metaDescription')->textarea(['rows' => 2,'class'=>'uk-width-1-1']) ?>

<?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true,'class'=>'uk-width-1-1']) ?>

<div class="uk-form-row">
    <?=Html::submitButton('Продолжить',['class'=>'uk-button uk-button-success'])?>
</div>

<?php ActiveForm::end(); 

print_r($model->errors);
        
$renderedElements = Json::encode($model->renderedElements);

// echo Html::hiddenInput('renderedElements', $renderedElements, ['option' => 'value']);

$js = <<<JS
var form = $("#form");
var renderedElements = $renderedElements;

form.on("change",'.category-select', function() {
    if (!form.hasClass('update')) {
        form.addClass('update');
        $.post(location.href, form.serialize() + '&' + $.param({reload:'true','renderedElements':renderedElements}),
            function(data){
                for (var i in data.removeElements) {
                    form.find(".element-"+data.removeElements[i]).html("");
                    form.yiiActiveForm('remove', 'element-'+data.removeElements[i]);
                }
                for (var i in data.renderElements) {
                    form.find(".element-"+i).html(data.renderElements[i]);
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