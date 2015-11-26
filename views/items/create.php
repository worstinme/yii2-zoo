<?php

use yii\helpers\Html;


$this->title = Yii::t('admin', 'Создание материала');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/default/application','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;


$js = <<<JS

var form = $("#form");
var rendered_fields_ids = new Array();

form.on("change",'.category-select', function() {
    if (!form.hasClass('update')) {
        form.addClass('update');
        console.log('перезагрузка формы');
        var form_data = form.serialize();  
        console.log('Данные формы:  '+form_data + '&' + $.param({reload: 'true', 'rendered_fields_ids': rendered_fields_ids}));
        $.post(location.href,form_data + '&' + $.param({reload: 'true', 'rendered_fields_ids': rendered_fields_ids}),
            function(data){
                for (var id in data.new_fields_renders) {
                    form.find("[data-field-place='"+id+"']").html(data.new_fields_renders[id]);
                }
                for (var id in data.fields_to_remove) {
                    form.find("[data-field-place='"+data.fields_to_remove[id]+"']").html("");
                }
                rendered_fields_ids = data.new_fields_ids;
                console.log('Загруженые поля:  '+rendered_fields_ids);
                console.log('Новые данные:  ', data);
                form.removeClass('update');
                $("#hidden-reload").val("false");
            }
        ); 

    }        
})


form.addClass('update');

$.post(location.href,{'reload':'true','rendered_fields_ids':rendered_fields_ids},function(data){
    
    for (var id in data.new_fields_renders) {
        form.find("[data-field-place='"+id+"']").html(data.new_fields_renders[id]);
    }
    for (var id in data.fields_to_remove) {
        form.find("[data-field-place='"+data.fields_to_remove[id]+"']").html("");
    }
    rendered_fields_ids = data.new_fields_ids;
    console.log('Загруженные поля: '+ rendered_fields_ids);
    form.removeClass('update');
});

JS;

$this->registerJs($js, $this::POS_READY);

?>
<div class="applications items-create">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

        <hr>

        <?= $this->render('_form', [
            'model' => $model,
            'app'=>$app,
        ]) ?>

	</div>

    <div class="uk-width-medium-1-5">
        <?=$this->render('/_nav',['app'=>$app])?>
    </div>

    

</div>

</div>
