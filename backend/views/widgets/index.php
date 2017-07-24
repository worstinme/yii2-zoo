<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\widgets\GridView;
use worstinme\uikit\widgets\ListView;
use worstinme\uikit\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Widgets';
$this->params['breadcrumbs'][] = $this->title;

$positions = $searchModel->query()->select(['position'])->distinct()->indexBy('position')->column();

?>
<div class="widgets-index">

    <?= Html::a(Yii::t('zoo','Создать виджет'), ['create'], ['class' => 'uk-button uk-button-success']); ?>

    <?php if (empty($searchModel->position)): ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=> ['class' => 'items'],
        'layout' => '{items}{pager}<hr>',
        'columns' => [
            [
                'attribute'=>'name',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->name,['update','id'=>$model->id]);
                },
            ],
            [
                'attribute'=>'state',
                'label'=>'',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::a('','#',['onClick' => "var link=$(this);$.ajax({url:'".Url::to(['update','id'=>$model->id])."',type:'POST', data: {'".$model->formName()."[state]':link.data('state')==0?1:0},success: function(data){if (data.success) {if(data.model.state == 1) link.attr('class','uk-icon-check-circle'); else link.attr('class','uk-icon-times-circle');link.data('state',data.model.state)}console.log(data);}});return false;",'class'=>"uk-icon-".($model->state==1 ? 'check' :'times')."-circle",'data'=>['pjax'=>0,'state'=>$model->state]]);
                },
                'headerOptions'=>['class'=>'uk-text-center'],
                'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                'attribute'=>'id',
                'label'=>'# callback',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return '[widget id='.$model->id.']';
                },
                'headerOptions'=>['class'=>'uk-text-center'],
                'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                'attribute'=>'type',
                'filter' => $searchModel->query()->select(['type'])->distinct()->indexBy('type')->column(),
            ],
            [
                'attribute'=>'position',
                'filter' => $positions,
            ],
            'bound',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[
                  'delete' => function ($url, $model) {     
                    return Html::a('<i class="uk-icon-trash"></i>', $url, [
                            'title' => 'Удалить',
                            'data'=>[
                                'method'=>'post',
                                'confirm'=>'Точно удалить?',
                            ],
                    ]);                                
                  },
                ],
                'contentOptions'=>['class'=>'uk-text-center'],                           
            ],
        ],
        
    ]); ?>

    <?php else: ?>

        <?php $dataProvider->pagination = false; ?>

        <div class="uk-display-inline-block uk-margin-left">

        <?php $form = ActiveForm::begin(['id' => 'login-form','method'=>'get']); ?>    

        <?= Html::activeDropDownList($searchModel, 'position', $positions, ['prompt'=>'Сбросить позицию']); ?>

        <?php ActiveForm::end(); ?>

        </div>

        <?=ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions'=>['tag'=>false],
            'layout' => '{items}',
            'options'=>['tag'=>'ul','class'=>'uk-nestable','data'=>['uk-nestable'=>'']],
            'itemView' => '_item',
        ])?>

        <?php  \worstinme\uikit\assets\Nestable::register($this);

        $url = Url::to(['sort']);

        $js = <<<JS

        $("#widgetssearch-position").on("change",function(){ $(this).parents("form").submit()});

        var nestable = UIkit.nestable('[data-uk-nestable]');
        
        nestable.on('change.uk.nestable',function(event,it,item,action){

            var sort = {}; 

            $("ul[data-uk-nestable] > li").each(function(index) {
                sort[$(this).data('item-id')] = index;
            });

            console.log(sort);

            $.post("$url",{'sort':sort}, function( data ) {
                console.log(data);
                UIkit.notify("Есть кптан");
            });

        });

JS;

        $this->registerJs($js,$this::POS_READY); ?>
        
    <?php endif ?>


</div>