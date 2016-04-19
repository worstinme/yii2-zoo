<?php

use yii\helpers\ArrayHelper;

$fields  = ArrayHelper::map(Yii::$app->controller->app->elements,'name','title');


?>

<?= $form->field($model, 'relatedField')->dropdownlist($fields,['prompt'=>'Выберите поле из которого будет формироваться алиас страницы']) ?>