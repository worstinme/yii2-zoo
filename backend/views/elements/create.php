<?php

use worstinme\zoo\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('zoo', 'ELEMENTS_SELECT_TYPE_TITLE');

$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'APPLICATIONS_INDEX_BREADCRUMB'), 'url' => ['/zoo/default/index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['/zoo/items/index', 'app' => $app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo', 'ELEMENTS_INDEX_BREADCRUMB'), 'url' => ['index', 'app' => $app->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="uk-panel uk-panel-box">
    <h2><?= $this->title ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'label')->textInput(['class' => 'uk-input'])  ?>

    <?= $form->field($model, 'name')->textInput(['placeholder'=>'^[\w_]+', 'class' => 'uk-input']) ?>

    <div class="uk-grid uk-child-width-1-5@s uk-grid-match uk-grid-small" uk-grid>
        <?php foreach ($elements as $element): ?>

        <div>
            <?= Html::a('<i class="uk-icon-cog"></i> ' . $element, null, [
                'class' => 'uk-button uk-button-primary',
                'data' => [
                    'method' => 'post',
                    'params' => [
                        $model->formname() . "[type]" => $element,
                    ]
                ]
            ]) ?>
        </div>
        <?php endforeach ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>