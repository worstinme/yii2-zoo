<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$form = ActiveForm::begin(['id'=>'form','action'=> \yii\helpers\Url::current(),'method'=>'get']); ?>

	<?= Html::dropDownList('renderer', $renderer, $renderersNames, 
		['class'=>'renderer-select','prompt'=>'выбрать шаблон вывода']); ?>

<?php ActiveForm::end(); 

$js = <<<JS

var form = $("#form");

form.on("change",'.renderer-select', function() {
   form.submit();
});

JS;

$this->registerJs($js, $this::POS_READY); 