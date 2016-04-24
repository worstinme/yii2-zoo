<?php

use yii\helpers\Html;

$this->registerJs($model->addValidators($this,$attribute), 5);

\worstinme\uikit\assets\Datepicker::register($this);

?>

<?= Html::activeLabel($model, $attribute,['class'=>'uk-form-label']); ?>

<div class="uk-from-controls">
	<?= Html::activeInput('text', $model, $attribute,['class'=>'uk-width-1-1','data'=>['uk-datepicker'=>"{format:'DD.MM.YYYY',i18n:{months:['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],weekdays:['Пн','Вт','Ср','Чт','Пт','Сб','Вс']}}"]]); ?>
</div>
