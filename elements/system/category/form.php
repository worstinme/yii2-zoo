<?php

use yii\helpers\Html;
use yii\helpers\Url;

$input_id = Html::getInputId($model, $element->attributeName);
$parent_input_id = Html::getInputId($model, 'parent_category_id');

\worstinme\zoo\backend\assets\Select2Asset::register($this);

$items = \worstinme\zoo\backend\models\Categories::buildTree($this->context->app->id, $model->lang);

$values = $model->{$element->attributeName};

?>

    <div class="uk-grid uk-grid-small" uk-grid>
        <div class="uk-width-2-3@m">
            <?= Html::activeDropDownList($model, $element->attributeName, $items, ['multiple' => true]) ?>
        </div>
        <div class="uk-width-1-3@m">
            <?= Html::activeDropDownList($model, 'parent_category_id', array_filter($items, function ($key) use ($values) {
                return is_array($values) && in_array($key, $values);
            }, ARRAY_FILTER_USE_KEY), ['class' => 'uk-select']) ?>
        </div>
    </div>


<?php

$callback_url = Url::to(['callback/' . $element->type, 'element' => $element->name, 'app' => $model->app_id]);

$script = <<<JS
    $('#$input_id').select2({width:'100%'});
    $(document)
        .on("change",".element_lang :input", function(e){
            $.post("$callback_url",{lang:$(this).val()}).then(function (data) {
                $('#$parent_input_id').val(null).html("");
                $('#$input_id').removeClass('select2-offscreen').select2('destroy').empty().select2({width:'100%', data:data});
            });
        })
        .on("change","#$input_id",function() {
            var values = $(this).val();
            $("[data-categories]").each(function(){
                var categories = $(this).data("categories");
                if  ((function(){
                        for (i in values) {
                            if  (categories.indexOf(values[i]) != -1) {
                                return true;
                            }
                        }
                        return false;
                    }())) { 
                    
                    $(this).removeAttr("hidden").find(":input").removeAttr("disabled");
                    $(this).find(".caegories-active").attr("disabled","disabled");
                    
                } else {
                
                    $(this).attr("hidden",true).find(":input").attr("disabled",true);
                    $(this).find(".caegories-active").removeAttr("disabled");
                    
                }
                
            });
        });
    
        $("#$input_id")
            .on("select2:unselect", function(e) {
                $("#$parent_input_id option[value='"+e.params.data.id+"']").remove();
            })
            .on("select2:select", function(e) {
                $('#$parent_input_id').append($("<option></option>")
                          .attr("value",e.params.data.id)
                          .text(e.params.data.text));
                if ($('#$parent_input_id').val() === undefined || $('#$parent_input_id').val() == '') {
                    $('#$parent_input_id').val(e.params.data.id)
                }
            });
JS;

$this->registerJs($script, $this::POS_READY) ?>