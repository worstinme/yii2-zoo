<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<div class="uk-form-row">
    <?= Html::activeLabel($model, 'relatedCategories', ['class'=>'uk-form-label']); ?>
    <div class="uk-form-controls">
        <?= \vova07\select2\Widget::widget([
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

