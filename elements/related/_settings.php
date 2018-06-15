<?php

use worstinme\zoo\backend\models\Categories;
use worstinme\zoo\models\Applications;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

worstinme\zoo\backend\assets\Select2Asset::register($this);

$input_id = Html::getInputId($model,'relatedCategories');

$this->registerJs("$('#".$input_id."').select2({width:'100%'});",$this::POS_READY);

$categories = Categories::find()->where(['app_id' => $model->app->id])->orderBy('sort ASC')->all();

$catlist = \worstinme\zoo\helpers\CategoriesHelper::processCatlist(ArrayHelper::toArray($categories, [
    Categories::className() => [
        'id',
        'parent_id',
        'name',
    ],
]));

?>


<?= $form->field($model, 'relatedApplication')->dropDownList(ArrayHelper::map(Yii::$app->zoo->applications,'id','title'), ['class'=>'uk-select','prompt'=>'Select app']); ?>

<div class="uk-form-row">
    <?= Html::activeLabel($model, 'relatedCategories', ['class'=>'uk-form-label']); ?>
    <div class="uk-form-controls">
        <?= Html::activeDropDownList($model, 'relatedCategories', $catlist, ['multiple'=>'multiple','class' => 'uk-select']) ?>
    </div>
</div>