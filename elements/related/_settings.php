<?php

use worstinme\zoo\models\Applications;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>


<?= $form->field($model, 'relatedApplication')->dropDownList(ArrayHelper::map(Applications::find()->where(['<>','id',$model->app_id])->all(),'id','name'), ['prompt'=>'Select app']); ?>

<div class="uk-form-row">
    <?= Html::activeLabel($model, 'relatedCategories', ['class'=>'uk-form-label']); ?>
    <div class="uk-form-controls">
        <?= \worstinme\zoo\helpers\Select2Widget::widget([
            'model' => $model,
            'attribute' => 'relatedCategories',
            'options' => [
                'multiple' => true,
                'placeholder' => 'Choose item',
            ],
            'settings' => [
                'width' => '100%',
            ],
            'items' => $model->app->catlist,
        ]) ?>
    </div>
</div>


<?= $form->field($model, 'viaTableName')->textInput(['option'=>'']); ?>

