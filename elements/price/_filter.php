<?php

use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\jui\SliderInput;

JuiAsset::register($this);

$id = uniqid();



?>

<label class="f-label">Цена, руб.</label>

<div class="price-filter">
    <?= Html::activeTextInput($searchModel, 'price_min',['placeholder'=>'от...']); ?>
    <?= Html::activeTextInput($searchModel, 'price_max',['placeholder'=>'до']); ?>
</div>

<div id="price-<?=$id?>" class="price-filter-slider"></div>

<?php 

$values = \yii\helpers\Json::encode($searchModel->price_min > 0  && $searchModel->price_max > 0 ? [$searchModel->price_min,$searchModel->price_max] : [15000,100000]);

$price_min_id = Html::getInputId($searchModel, 'price_min');
$price_max_id = Html::getInputId($searchModel, 'price_max');

$js = <<<JS

$( "#price-$id" ).slider({
    range: true,
    min: 0,
    max: 150000,
    step: 500,
    values: $values,  
    slide: function( event, ui ) {
      $( "#$price_min_id" ).val(ui.values[0]);
      $( "#$price_max_id" ).val(ui.values[1]);
    }
});

//$( "#itemssearch-price_min" ).val($( "#slider-range" ).slider( "values", 0 ));
//$( "#itemssearch-price_max" ).val($( "#slider-range" ).slider( "values", 1 ));

JS;

$this->registerJs($js, $this::POS_READY); ?>