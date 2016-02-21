<?php

use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\uikit\ActiveForm;

$template = Yii::$app->controller->app->getTemplate('form');


$form = ActiveForm::begin(['id'=>'form', 'layout'=>'stacked', 'enableClientValidation' => false]);

if (count($template)) {
    foreach ($template as $row) {
        if (count($row['items'])) {
            echo $this->render('rows/'.$row['type'],[
                'row'=>$row,
                'model'=>$model,
                'view'=>'_form',
            ]);    
        }
    }
}

?>

<div class="uk-form-row">
    <?=Html::submitButton('Продолжить',['class'=>'uk-button uk-button-success'])?>
</div>

<?php ActiveForm::end(); 

$renderedElements = Json::encode($model->renderedElements);

$js = <<<JS
var form = $("#form");
var renderedElements = $renderedElements;

form.on("change",'.category-select', function() {
    if (!form.hasClass('update')) {
        form.addClass('update');
        $.post(location.href, form.serialize() + '&' + $.param({reload:'true','renderedElements':renderedElements}),
            function(data){
                for (var i in data.removeElements) {
                    form.find(".field-"+data.removeElements[i]).html("");
                    form.yiiActiveForm('remove', 'field-'+data.removeElements[i]);
                }
                for (var i in data.renderElements) {
                    form.find(".field-"+i).html(data.renderElements[i]);
                }                
                renderedElements = data.renderedElements;
                console.log(data);
                form.removeClass('update');
            }
        ); 
    }        
})
JS;

$this->registerJs($js, $this::POS_READY);