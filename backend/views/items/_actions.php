<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


?>

<div class="items-actions">
<ul class="uk-subnav uk-subnav-line" style="margin-bottom: 0">
    <li><span>Применить <span class="selected-count"></span>:</span></li>
    <li><a href="#actions-category" data-uk-modal>Присвоить категории</a></li>
    <li>
        <?= Html::a('Удалить категории', Url::current(), [
            'id'=>'deleteCategories',
            'data'=>['method'=>'post','confirm'=>'Уверены что хотите очистить категории у найденных материалов?',]
        ]); ?>
    </li>
</ul>
</div>

<div id="actions-category" class="uk-modal">
    <div class="uk-modal-dialog">

        <a class="uk-modal-close uk-close"></a>
        
        <div class="uk-form-row">

        <div class="uk-scrollable-box" style="min-height: calc(100vh - 200px)">
            <ul class="uk-list">
                <?php foreach (Yii::$app->controller->app->parentCategories as $category): ?>
                    <?=$this->render('_actions_category',['category'=>$category])?>
                <?php endforeach ?>
            </ul> 
            </ul>
        </div>

        </div>

        <div class="uk-form-row">

            <?= Html::a('Заменить категории на выбранные', Url::current(), [ 'id'=>'replaceCategories', 
                    'class'=>'uk-button uk-button-small uk-button-success',
                    'data'=>[ 'method'=>'post', ], 
                ]); ?>

            <?= Html::a('Добавить категории', Url::current(), [ 'id'=>'addCategories', 
                    'class'=>'uk-button uk-button-small uk-button-primary uk-float-right',
                    'data'=>[ 'method'=>'post', ], 
                ]); ?>
            
            <? //=Html::submitButton('Заменить',['name'=>'replaceCategories','class'=>'uk-button uk-button-primary'])?>
            <? //=Html::submitButton('Добавить к имеющимся',['name'=>'addCategories','class'=>'uk-button uk-button-success'])?>
            
            
        </div>

       

    </div>
</div>



<?php 

$js = <<<JS

$("#catalogue .items th:first-child input[type='checkbox'],#catalogue .items td:first-child input[type='checkbox']").on("change", function() {
    var keys = $('#catalogue .items').yiiGridView('getSelectedRows');
    if (keys.length > 0) {
        $("#catalogue .selected-count").html(keys.length);
    }
    else {
        $("#catalogue .selected-count").html("");
    } 
});

$("body").on("click", "#replaceCategories", function(e) {

    var selection = $('#catalogue .items').yiiGridView('getSelectedRows');
    var categoryIds = $("#form input.category-list:checked").map(function () { return $(this).val(); }).get();
    var params = JSON.stringify({categoryIds,selection,replaceCategories:true});
    
    $(this).attr('data-params', params);

});
$("body").on("click", "#addCategories", function(e) {

    var selection = $('#catalogue .items').yiiGridView('getSelectedRows');
    var categoryIds = $("#form input.category-list:checked").map(function () { return $(this).val(); }).get();
    var params = JSON.stringify({categoryIds,selection,addCategories:true});
    
    $(this).attr('data-params', params);

});
$("body").on("click", "#deleteCategories", function(e) {

    var selection = $('#catalogue .items').yiiGridView('getSelectedRows');
    var params = JSON.stringify({selection,deleteCategories:true});

    $(this).attr('data-params', params);
    console.log(params);
});

JS;

$this->registerJs($js, $this::POS_READY); 