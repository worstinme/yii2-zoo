<?php

$fields = \worstinme\zoo\models\Elements::find()->where(['app_id'=>Yii::$app->controller->application->id,'type'=>'textfield'])->all();

$fields = \yii\helpers\ArrayHelper::map($fields,'id','title');

?>

<?= $form->field($model, 'related_fields')->dropDownList($fields,['multiple'=>'multiple']) ?>