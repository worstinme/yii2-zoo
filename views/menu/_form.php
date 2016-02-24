<?php

use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\uikit\ActiveForm;

$template = Yii::$app->controller->app->getTemplate('form');

$form = ActiveForm::begin(['id'=>'form', 'layout'=>'stacked', 'enableClientValidation' => false]); ?>

        
<div class="uk-grid uk-margin-top">
    <div class="uk-width-medium-1-3">
    <?= $form->field($model, 'type')->dropDownList($model->types,['prompt'=>Yii::t('admin','Выбрать тип меню'),'class'=>'uk-width-1-1'])  ?>
    </div>
    <div class="uk-width-medium-1-3">
    <?= $form->field($model, 'menu')->textInput(['class'=>'uk-width-1-1'])  ?>
    </div>
    <div class="uk-width-medium-1-3">
    </div>
    <div class="uk-width-medium-1-3 uk-grid-margin">
    <?php if ($model->applications): ?>
        <?= $form->field($model, 'application_id')->dropDownList($model->applications,['prompt'=>Yii::t('admin','Выбрать приложение'),'class'=>'uk-width-1-1'])  ?>
    <?php endif ?>
    </div>
    <div class="uk-width-medium-1-3 uk-grid-margin">
    <?php if ($model->categories): ?>
        <?= $form->field($model, 'category_id')->dropDownList($model->categories,['prompt'=>Yii::t('admin','Выбрать категорию'),'class'=>'uk-width-1-1'])  ?>
    <?php endif ?>
    </div>
    <div class="uk-width-medium-1-3 uk-grid-margin">
    <?php if ($model->items): ?>
        <?= $form->field($model, 'item_id')->dropDownList($model->items,['prompt'=>Yii::t('admin','Выбрать материал'),'class'=>'uk-width-1-1'])  ?>
    <?php endif ?>
    </div>
</div>
<div class="uk-margin-top">

    <?php if ($model->type==4 || $model->type==5): ?>
        <?= $form->field($model, 'url')->textarea(['class'=>'uk-width-1-1','rows'=>6])  ?>
    <?php endif ?>

    <?php if ($model->check): ?>

    <?= $form->field($model, 'label')->textInput(['class'=>'uk-width-1-1'])  ?>

    <?= $form->field($model, 'class')->textInput(['class'=>'uk-width-1-1'])  ?>

    <?= $form->field($model, 'parent_id')->dropDownList($model->parents,['prompt'=>Yii::t('admin','Родительский пункт меню')])  ?>

    <div class="uk-form-row">
        <?=Html::submitButton('Продолжить',['class'=>'uk-button uk-button-success'])?>
    </div>

    <?php endif ?>

</div>



<?php ActiveForm::end(); 

$js = <<<JS

var form = $("#form");

form.on("change","select", function() {
    form.submit();
});

JS;

$this->registerJs($js, $this::POS_READY);