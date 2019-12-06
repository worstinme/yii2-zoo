<?php

use worstinme\zoo\backend\models\BackendItems;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use worstinme\zoo\widgets\ActiveForm;

/** @var $model BackendItems */

$items = ArrayHelper::map(BackendItems::find()
    ->where(['app_id' => $model->app_id])
    ->andFilterWhere(['<>', 'id', $model->id])
    ->orderBy('lang, name')
    ->all(), 'id', function ($model) {
    return $model->name . ' / ' . strtoupper($model->lang);
});

?>

<?php $form = ActiveForm::begin(['id' => 'form', 'options' => ['class' => 'uk-form-stacked']]); ?>

    <div class="uk-grid" uk-grid>
        <div class="uk-width-2-3@m">
            <?= $form->field($model, 'element_name')->element(); ?>
            <?= $form->field($model, 'element_category')->element(); ?>
        </div>
        <div class="uk-width-1-3@m">
            <div class="uk-width-medium-1-3">
                <?= $form->field($model, 'element_lang')->element(); ?>
                <div class="uk-grid uk-child-width-1-2@m" uk-grid>
                    <div>
                        <?= $form->field($model, 'state')->checkbox(); ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'flag')->checkbox(['option' => 'value']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php foreach ($model->app->elements as $element) : ?>
    <?= $form->field($model, $element->attributeName)->element(); ?>
<?php endforeach; ?>
    <div class="uk-grid">
        <div class="uk-width-2-3@m">
            <?= $form->field($model, 'element_alternate')->element(); ?>
            <?= $form->field($model, 'element_meta_title')->element(); ?>
            <?= $form->field($model, 'element_meta_description')->element(); ?>
            <?= $form->field($model, 'element_meta_keywords')->element(); ?>
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
