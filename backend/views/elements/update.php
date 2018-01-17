<?php

use worstinme\zoo\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->label;

$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/items/index','app'=>Yii::$app->controller->app->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Элементы'), 'url' => ['/'.Yii::$app->controller->module->id.'/elements/index','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;

$all_categories_input_id = Html::getInputId($model, 'all_categories');

$js = <<<JS

	$("#$all_categories_input_id").on("click",function(){
		console.log($(this).val());
		if ($(this).is(":checked")) {
			$('.categories').addClass('uk-hidden')
		}
		else {
			$('.categories').removeClass('uk-hidden')
		} 
	})

JS;

$this->registerJs($js, $this::POS_READY);

?>

<div class="uk-panel uk-panel-box">		
<h2><?=$this->title?></h2>

<?php $form = ActiveForm::begin(['options'=>['class'=>'uk-form-stacked']]); ?>

	<div class="uk-grid">

		<div class="uk-width-2-3@m uk-margin">

			<?= $form->field($model, 'type')->textInput(['disabled'=>'disabled','class'=>'uk-input'])?>

		    <?= $form->field($model, 'name')->textInput(['disabled'=>'disabled','class'=>'uk-input'])  ?>

			<?= $form->field($model, 'label')->textInput(['class'=>'uk-input'])  ?>

		    <?= $form->field($model, 'hint')->textarea(['rows'=>2,'class'=>'uk-textarea'])  ?>


            <?php if (!empty($model->configView)): ?>

                <?=$this->render($model->configView,[
                    'model'=>$model,
                    'form'=>$form,
                ])?>

            <?php endif ?>

            <legend>Зависимости:</legend>

            <?= $form->field($model, 'all_categories')->checkbox(['class'=>'uk-checkbox']) ?>

            <?= $form->field($model, 'categories',['options'=>['class'=>'categories uk-margin '.($model->all_categories == 1?'uk-hidden':'')]])->dropdownlist($catlist,['class'=>'uk-select','multiple' => 'multiple','size'=>(count($catlist) > 9 ? 10 : count($catlist))]) ?>


        </div>

        <div class="uk-width-1-3@m uk-margin">

            <?= $form->field($model, 'required')->checkbox(['class'=>'uk-checkbox']) ?>

            <?= $form->field($model, 'admin_filter')->checkbox(['class'=>'uk-checkbox']) ?>

            <?= $form->field($model, 'filter')->checkbox(['class'=>'uk-checkbox']) ?>

            <?= $form->field($model, 'refresh')->checkbox(['class'=>'uk-checkbox']) ?>

            <?php if ($model->itemsStore !== null) : ?>
                <?= $form->field($model, 'own_column')->checkbox(['class'=>'uk-checkbox']) ?>
            <?php endif; ?>

            <?= $form->field($model, 'sorter')->checkbox(['class'=>'uk-checkbox']) ?>

            <hr>

            <div class="uk-form-row uk-margin-large-top">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('zoo','Создать') : Yii::t('zoo','Сохранить'),['class'=>'uk-button uk-button-success uk-button-large']) ?>
            </div>

        </div>
		

	</div>

<?php ActiveForm::end(); ?>
</div>