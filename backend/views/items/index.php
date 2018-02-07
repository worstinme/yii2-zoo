<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use worstinme\uikit\ActiveForm;

$this->title = Yii::$app->controller->app->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php  \yii\widgets\Pjax::begin(['id'=>'catalogue','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summaryOptions'=>['class'=>'uk-text-center'],
    'tableOptions'=> ['class' => 'uk-table uk-table-striped uk-table-hover uk-table-small'],
    'options'=> ['class' => 'items'],
    'layout' => '{items}{summary}{pager}',
    'pager' => ['class'=> 'worstinme\zoo\helpers\LinkPager'],
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute' => 'id',
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'search',
            'label'=>'Наименование',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a($model->name,['create','app'=>$model->app_id, 'id'=>$model->id],['data'=>['pjax'=>0]])." ".Html::a('url', $model->url, ['data-pjax'=>0,'target'=>'_blank','title' => Yii::t('zoo', 'Открыть на сайте'),
                        'target'=>'_blank','data'=>['pjax'=>false], 'style'=>'float:right;color:#468847',
                ]);
            },
        ],
        [
            'attribute'=>'state',
            'label'=>'',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a('','#',['onClick' => "var link=$(this);
                        $.ajax({url:'".Url::to(['create','app'=>$model->app_id, 'id'=>$model->id])."',type:'POST',
                            data: {'".$model->formName()."[state]':link.data('state')==0?1:0},
                            success: function(data){
                                if (data.success) {
                                    if(data.model.state == 1) link.attr('class','uk-icon-check-circle'); 
                                    else link.attr('class','uk-icon-times-circle'); 
                                    link.data('state',data.model.state)
                                }
                            }
                        })",'class'=>"uk-icon-".($model->state==1 ? 'check' :'times')."-circle",'data'=>['pjax'=>0,'state'=>$model->state]]);
            },
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'flag',
            'label'=>'',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a('','#',['onClick' => "var link=$(this);
                        $.ajax({url:'".Url::to(['create','app'=>$model->app_id, 'id'=>$model->id])."',type:'POST',
                            data: {'".$model->formName()."[flag]':link.data('flag')==0?1:0},
                            success: function(data){
                                if (data.success) {
                                    if(data.model.flag == 1) link.attr('class','uk-icon-star'); 
                                    else link.attr('class','uk-icon-star-o'); 
                                    link.data('flag',data.model.flag)
                                }
                            }
                        })",'class'=>"uk-icon-star".($model->flag ? '' :'-o'),'data'=>['pjax'=>0,'flag'=>$model->flag]]);
            },
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'lang',
            'filter'=>Yii::$app->zoo->languages,
            'value'=>function($model) {
                return $model->lang ? $model->lang : '—';
            },
            'headerOptions'=>['class'=>'uk-text-center','style'=>'width:60px;'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'element_category',
            'filter'=>$catlist,
            'label'=>'Категории',
            'format' => 'html',
            'value' => function ($model, $index, $widget) {
                
                if (count($model->categories)) {
                    $category_ = [];
                    foreach ($model->categories as $category) {
                        $category_[] = $category->name;
                    }
                    return implode(" / ",$category_);
                }
                else return '—';
            },
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{delete}',
            'buttons'=>[
              'delete' => function ($url, $model) {     
                return Html::a('<i uk-icon="icon: trash"></i>', $url, [
                        'title' => Yii::t('zoo', 'DELETE'),
                        'data'=>[
                            'method'=>'post',
                            'confirm'=>'DELETE_CONFIRM',
                        ],
                ]);                                
              },
            ],
            'contentOptions'=>['class'=>'uk-text-center'],                           
        ],
    ],
]); ?>

<?php $form = ActiveForm::begin(['id'=>'form', 'layout'=>'stacked']); ?>

    <?=$this->render('_actions',[
        'parentCategories'=>$parentCategories,
    ])?>

<?php ActiveForm::end(); ?>

<?php  \yii\widgets\Pjax::end(); ?>





       
            
       