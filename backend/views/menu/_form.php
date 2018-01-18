<?php

use yii\helpers\Html;
use yii\helpers\Json;
use worstinme\zoo\widgets\ActiveForm;
use conquer\codemirror\CodemirrorWidget;
use conquer\codemirror\CodemirrorAsset;

$form = ActiveForm::begin(['id' => 'form', 'options' => ['class' => 'uk-form-stacked'], 'enableClientValidation' => false]); ?>

    <div class="uk-grid uk-grid-small uk-margin-top" uk-grid>
        <div class="uk-width-1-3@m">
            <?= $form->field($model, 'menu')->textInput(['class' => 'uk-input']) ?>
        </div>
        <div class="uk-width-1-3@m">
            <?= $form->field($model, 'type')->dropDownList($model->types, ['prompt' => Yii::t('zoo', 'Выбрать тип меню'), 'class' => 'uk-select']) ?>
        </div>
        <div class="uk-width-1-3@m">
            <?php if (count(Yii::$app->zoo->languages)) : ?>
                <?= $form->field($model, 'lang')->dropDownList(Yii::$app->zoo->languages, ['class' => 'uk-select']) ?>
            <?php else: ?>
                <?= $form->field($model, 'lang')->textInput(['class' => 'uk-input uk-disabled', 'disabled' => 'disabled']) ?>
            <?php endif; ?>

        </div>
        <div class="uk-width-1-1">
            <div class="uk-grid uk-grid-small uk-child-width-1-3@m" uk-grid>
                <?php if ($model->type == 6): ?>
                    <div>
                        <?= $form->field($model, 'content')->widget(
                            CodemirrorWidget::className(),
                            [
                                'assets' => [
                                    CodemirrorAsset::ADDON_EDIT_CLOSETAG,
                                    CodemirrorAsset::ADDON_FOLD_XML_FOLD,
                                    CodemirrorAsset::MODE_XML,
                                    CodemirrorAsset::MODE_JAVASCRIPT,
                                    CodemirrorAsset::MODE_CSS,
                                    CodemirrorAsset::MODE_HTMLMIXED,
                                ],
                                'settings' => [
                                    'lineNumbers' => true,
                                    'mode' => 'text/html',
                                    'autoCloseTags' => true,
                                ],
                            ]
                        ); ?>
                    </div>
                <?php endif ?>

                <?php if ($model->type == 5): ?>
                    <div>
                        <?= $form->field($model, 'link')->textInput(['class' => 'uk-input']) ?>
                    </div>
                <?php endif ?>

                <?php if ($model->type == 4): ?>
                    <div>
                        <?= $form->field($model, 'content')->widget(
                            CodemirrorWidget::className(),
                            [
                                'assets' => [
                                    CodemirrorAsset::ADDON_EDIT_CLOSETAG,
                                    CodemirrorAsset::ADDON_FOLD_XML_FOLD,
                                    CodemirrorAsset::MODE_JAVASCRIPT,
                                ],
                                'settings' => [
                                    'lineNumbers' => true,
                                    'mode' => 'application/json',
                                    'autoCloseTags' => true,
                                ],
                            ]
                        ); ?>
                    </div>
                <?php endif ?>

                <?php if ($model->type == 1 || $model->type == 2 || $model->type == 3): ?>
                    <div>
                        <?= $form->field($model,'application')->dropDownList(is_array($applications)?$applications:[],['prompt'=>Yii::t('zoo','PROMPT_SELECT'),'class'=>'uk-select'])?>
                    </div>
                <?php endif ?>

                <?php if ($model->type == 3 || $model->type == 2): ?>
                    <div>
                        <?= $form->field($model,'category')->dropDownList(is_array($categories)?$categories:[],['prompt'=>Yii::t('zoo','PROMPT_SELECT'),'class'=>'uk-select'])?>
                    </div>
                <?php endif ?>

                <?php if ($model->type == 3): ?>
                    <div>
                        <?= $form->field($model,'item')->dropDownList(is_array($items)?$items:[],['prompt'=>Yii::t('zoo','PROMPT_SELECT'),'class'=>'uk-select'])?>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <div class="uk-width-2-3@m">
            <?= $form->field($model, 'name')->textInput(['class' => 'uk-input']) ?>
        </div>
        <div class="uk-width-1-3@m">
            <?= $form->field($model, 'parent_id')->dropDownList($model->parents, ['class' => 'uk-select', 'prompt' => Yii::t('zoo', 'PARENT_MENU_ITEM')]) ?>
        </div>
    </div>
    <div class="uk-margin-top">
        <?= Html::submitButton(Yii::t('zoo','SAVE_BUTTON'), ['class' => 'uk-button uk-button-success']) ?>
    </div>

<?php ActiveForm::end();
$type_id = Html::getInputId($model, 'type');
$lang_id = Html::getInputId($model, 'lang');
$application_id = Html::getInputId($model, 'application');
$item_id = Html::getInputId($model, 'item');
$category_id = Html::getInputId($model, 'category');

$js = <<<JS

var form = $("#form");

form.on("change","#$type_id,#$application_id,#$item_id,#$category_id,#$lang_id", function() {
    form.append('<input type="hidden" name="reload" value="true"/>');
    form.submit();
});

JS;

$this->registerJs($js, $this::POS_READY);