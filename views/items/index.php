<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('admin', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications items-index">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-bordered'],
        'options'=> ['class' => 'items'],
        'layout' => '{items}{pager}',
        'columns' => [
            [
                'attribute'=>'name',
                'label'=>'name',
                'format' => 'html',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->name,['create','app'=>$model->app_id, 'id'=>$model->id]);
                },
                //'headerOptions'=>['class'=>'uk-text-center'],
                //'contentOptions'=>['class'=>'uk-text-center'],
            ],//'contentOptions'=>['class'=>'uk-text-center'],
            'user_id',
            'flag',
            'sort',
            // 'state',
            // 'created_at',
            // 'updated_at',
            // 'params:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    </div>

    <div class="uk-width-medium-1-5">
        <?=$this->render('/_nav')?>
    </div>


</div>

</div>
