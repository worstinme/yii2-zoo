<?php

use worstinme\uikit\ActiveForm;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? Yii::t('admin','Создание категории') : Yii::t('admin','Изменение категории').':'.$model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['application','app'=>$app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Категории'), 'url' => ['categories','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="applications update uk-margin-top">

<h2><?=Yii::t('admin','Создание категории')?></h2>

<?php $form = ActiveForm::begin(['id' => 'login-form','layout'=>'stacked','field_width'=>'large','field_size'=>'large']); ?>
                    
    <?= $form->field($model, 'name')->textInput()  ?>

    <?= $form->field($model, 'alias')->textInput()  ?>

    <?= $form->field($model, 'parent_id')
		    	->dropDownList($app->catlist,['prompt'=>Yii::t('admin','Корневая категория')]); ?> 

    <div class="uk-form-row uk-margin-large-top">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('admin','Создать') : Yii::t('admin','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>