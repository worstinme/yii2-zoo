<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('zoo', 'Menu');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications items-index">

    <?= Html::a(Yii::t('zoo','Добавить пункт меню'), ['update'], ['class' => 'uk-button uk-button-success']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-bordered uk-margin-top'],
        'options'=> ['class' => 'items'],
        'layout' => '{items}{pager}<hr>',
        'pager' => ['class'=> 'worstinme\uikit\widgets\LinkPager'],
        'columns' => [
            [
                'attribute'=>'id',
                'label'=>'#',
                'headerOptions'=>['class'=>'uk-text-center','style'=>'width:20px;'],
                'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                'attribute'=>'name',
                'label'=>'Наименование',
                'format' => 'html',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->name,['update', 'menu'=>$model->id]);
                },
                //'headerOptions'=>['class'=>'uk-text-center'],
                //'contentOptions'=>['class'=>'uk-text-center'],
            ],
            'type',
            [
                'attribute'=>'menu',
                'filter'=>$groups,
                //'headerOptions'=>['class'=>'uk-text-center'],
                //'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{delete}',
                    'buttons'=>[
                      'delete' => function ($url, $model) {     
                        return Html::a('<i class="uk-icon-trash"></i>', $url, [
                                'title' => Yii::t('zoo', 'Удалить'),
                                'data'=>[
                                    'confirm'=>'Are u sure?',
                                    'method'=>'post',
                                ],
                        ]);                                
                      },
                    ],
                    'contentOptions'=>['class'=>'uk-text-center'],                           
                ],
        ],
    ]); ?>
    

</div>
