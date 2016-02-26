<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('backend','Создание приложения') : Yii::t('backend','Изменение приложения').':'.$model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="applications update uk-margin-top">

<h2><?=Yii::t('backend','Создание приложения')?></h2>

<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large','field_size'=>'large']); ?>
                    
    <?= $form->field($model, 'title')->textInput()  ?>

    <?= $form->field($model, 'name')->textInput()  ?>
                 
    <div class="uk-form-row">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend','Создать') : Yii::t('backend','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>