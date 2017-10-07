<?php

use worstinme\zoo\models\Items;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\uikit\ActiveForm;

$rows = $model->getTemplateRows('form');

$items = ArrayHelper::map(Items::find()
    ->where(['app_id' => $model->app_id])
    ->andFilterWhere(['<>', 'id', $model->id])
    ->orderBy('lang, name')
    ->all(), 'id', function ($model) {
    return $model->name . ' / ' . strtoupper($model->lang);
});


?>

<div class="uk-panel uk-panel-box">

    <?php $form = ActiveForm::begin(['id' => 'form', 'layout' => 'stacked', 'enableClientValidation' => false]);

    if (count($model->errors)) {
        echo $form->errorSummary($model);
    }

    echo \worstinme\zoo\helpers\TemplateHelper::render($model, 'form');

    ?>


    <hr>

    <div class="uk-grid">

        <div class="uk-width-medium-2-3">

            <?= $form->field($model, 'alternateIds')->widget(\worstinme\zoo\helpers\Select2Widget::className(), [
                'options' => [
                    'multiple' => true,
                    'placeholder' => 'Choose alternates'
                ],
                'settings' => [
                    'width' => '100%',
                ],
                'items' => $items,
            ]); ?>

            <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true, 'class' => 'uk-width-1-1']) ?>

            <?= $form->field($model, 'metaDescription')->textarea(['rows' => 2, 'class' => 'uk-width-1-1']) ?>

            <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true, 'class' => 'uk-width-1-1']) ?>


        </div>

        <div class="uk-width-medium-1-3">

            <?= $form->field($model, 'state')->dropDownList([
                $model::STATE_HIDDEN => 'Hidden',
                $model::STATE_ACTIVE => 'Active',
            ], ['option' => 'value']); ?>

            <?= $form->field($model, 'flag')->checkbox(['option' => 'value'])->label('&nbsp;'); ?>

        </div>

    </div>

    <div class="uk-form-row uk-margin-top">
        <?= Html::submitButton('Сохранить', ['name' => 'save', 'value' => 'continue', 'class' => 'uk-button uk-button-success']) ?>
        <?= Html::submitButton('Сохранить и закрыть', ['name' => 'save', 'value' => 'close', 'class' => 'uk-button uk-button-primary']) ?>
        <?php if (!$model->isNewRecord): ?>
            <?= Html::submitButton('Создать дубликат', ['name' => 'duplicate', 'value' => '1', 'class' => 'uk-button uk-button-danger']) ?>
        <?php endif ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php

$refresh_el = ['.category-select'];

foreach ($model->elements as $element) {
    if ($element->refresh) {
        $refresh_el[] = "#" . Html::getInputId($model, $element->name);
    }
    if ($element->related) {
        $refresh_el[] = "#" . Html::getInputId($model, $element->related);
    }
}

$refresh_el = implode(",", $refresh_el);

$renderedElements = Json::encode($model->renderedElements);

// echo Html::hiddenInput('renderedElements', $renderedElements, ['option' => 'value']);

$action = \yii\helpers\Url::to(['create', 'app' => $model->app_id]);

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
.on("click","[name=duplicate]", function(){
    form.attr("action","$action");
});
JS;

$this->registerJs($js, $this::POS_READY); ?>

</div>