<?php

use yii\helpers\ArrayHelper;

$fields  = ArrayHelper::map(Yii::$app->controller->app->fields,'name','title');


?>

<?= $form->field($model, 'relatedField')->dropdownlist($fields,['prompt'=>'Выберите поле из которого будет формироваться алиас страницы']) ?>