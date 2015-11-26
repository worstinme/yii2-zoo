<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

$template = $app->getTemplate('form');
$fields = $model->fields;
$expectedFields = $model->expectedFields;


$form = ActiveForm::begin(['id'=>'form',
                            'layout'=>'stacked',
                            'enableClientValidation' => false,
                            'options' => ['data-pjax' => true],
                        ]);

if (count($template)) 
    foreach ($template as $row) {
        
        $items = [];

        if (count($row['items'])) 
        foreach ($row['items'] as $key=>$r) {
            foreach ($r as $k=>$d) {
                if ($fields[$d] !== null && false) {
                    $items[$key][] = $this->render('@worstinme/zoo/fields/'.$fields[$d]->type.'/_form.php',[
                            'model'=>$model,
                            'app'=>$app,
                            'field'=>$fields[$d],
                            'form'=>$form,
                            'id'=>$d,
                        ]);
                }  

                if(in_array($d, $expectedFields)) {
                    $items[$key][] = '<div class="uk-form-row" data-field-place="'.$d.'"></div>';
                }             
            }
        }


        echo $this->render('rows/'.$row['type'],['name'=>$row['name'],'class'=>$row['class'],'items'=>$items]);

    } ?>

    <div class="uk-form-row">
        <?=Html::submitButton('Продолжить',['class'=>'uk-button uk-button-success'])?>
    </div>

    <hr>
    <?php print_r(Yii::$app->request->post()); ?>
    <hr>
    <?php print_r($model->errors); 

    ActiveForm::end(); 