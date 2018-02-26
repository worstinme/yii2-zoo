<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use worstinme\zoo\widgets\ActiveForm;

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
    'pager' => ['class'=> 'worstinme\zoo\widgets\LinkPager'],
    'columns' => [
       /* [
            'class' => 'yii\grid\CheckboxColumn',
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ], */
        [
            'attribute' => 'id',
            'headerOptions'=>['class'=>'uk-text-center','style'=>'width:10px;'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'search',
            'label'=>'Наименование',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a($model->name,['create','app'=>$model->app_id, 'id'=>$model->id],['data'=>['pjax'=>0]]);
            },
        ],
        [
            'label'=>'#',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a('<span uk-icon="link">', $model->url, ['data-pjax'=>0,'target'=>'_blank','title' => Yii::t('zoo', 'Открыть на сайте'),
                        'target'=>'_blank','data'=>['pjax'=>false],
                    ]);
            },
            'headerOptions' => ['class' => 'uk-text-center'],
            'contentOptions' => ['class' => 'uk-text-center'],
        ],
        [
            'attribute' => 'state',
            'label' => '',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a('<i uk-icon="icon: check" style="color: darkgreen"></i>', '#', ['onClick' => "var link=$(this);$.ajax({url:'" . \yii\helpers\Url::to(['create','app'=>$model->app_id, 'id'=>$model->id]) . "',type:'POST',data: {'" . $model->formName() . "[state]':0},success: function(data){ if(data.success) { $('.state-button-$model->id').toggleClass('uk-hidden'); } }})", 'class' => "state-button-".$model->id.($model->state ? null : " uk-hidden")]).
                    Html::a('<i uk-icon="icon: check" style="color: #ccc"></i>', '#', ['onClick' => "var link=$(this);$.ajax({url:'" . \yii\helpers\Url::to(['create','app'=>$model->app_id, 'id'=>$model->id]) . "',type:'POST',data: {'" . $model->formName() . "[state]':1},success: function(data){ if(data.success) { $('.state-button-$model->id').toggleClass('uk-hidden'); } }})", 'class' => "state-button-".$model->id.($model->state ? " uk-hidden" : null)]);
            },
            'headerOptions' => ['class' => 'uk-text-center'],
            'contentOptions' => ['class' => 'uk-text-center'],
        ],
        [
            'attribute' => 'flag',
            'label' => '',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a('<i uk-icon="icon: star" style="color: gold"></i>', '#', ['onClick' => "var link=$(this);$.ajax({url:'" . \yii\helpers\Url::to(['create','app'=>$model->app_id, 'id'=>$model->id]) . "',type:'POST',data: {'" . $model->formName() . "[flag]':0},success: function(data){ if(data.success) { $('.flag-button-$model->id').toggleClass('uk-hidden'); } }})", 'class' => "flag-button-".$model->id.($model->flag ? null : " uk-hidden")]).
                    Html::a('<i uk-icon="icon: star" style="color: #ccc"></i>', '#', ['onClick' => "var link=$(this);$.ajax({url:'" . \yii\helpers\Url::to(['create','app'=>$model->app_id, 'id'=>$model->id]) . "',type:'POST',data: {'" . $model->formName() . "[flag]':1},success: function(data){ if(data.success) { $('.flag-button-$model->id').toggleClass('uk-hidden'); } }})", 'class' => "flag-button-".$model->id.($model->flag ? " uk-hidden" : null)]);
            },
            'headerOptions' => ['class' => 'uk-text-center'],
            'contentOptions' => ['class' => 'uk-text-center'],
        ],
        [
            'attribute'=>'language',
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

<?php $form = ActiveForm::begin(['id'=>'form','options'=>['class'=>'uk-form-stacked']]); ?>

    <?=$this->render('_actions',[
        'parentCategories'=>$parentCategories,
    ])?>

<?php ActiveForm::end(); ?>

<?php  \yii\widgets\Pjax::end(); ?>





       
            
       