<?php

use yii\helpers\Html;
use yii\helpers\Json;

/*if ($field->getParam('editor')) {

	$options = Json::htmlEncode($form->field($field, $field->name)->clientOptions);

	echo "<script type=\"text/javascript\">jQuery(document).ready(function () { jQuery('#form').yiiActiveForm('add', $options);});</script>";

	echo $form->field($field, $field->name)->widget(\dosamigos\ckeditor\CKEditor::className(), [
        'options' => ['rows' => '9'],
        'preset' => 'basic'
    ])->label($field->title);
    
}
else {
	echo $form->field($field, $field->name)->textarea(['class'=>'uk-width-1-1'])->label($field->title);
}*/ ?>


<?= Html::activeTextarea($field, $field->name, ['option' => $field->value]); ?>	

<?= \vova07\imperavi\Widget::widget([
    'selector' => '#field-opisanie',
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
]);