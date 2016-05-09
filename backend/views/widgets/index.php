<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Widgets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widgets-index">

    <?= Html::a(Yii::t('backend','Создать виджет'), ['create'], ['class' => 'uk-button uk-button-success']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summaryOptions'=>['class'=>'uk-text-center'],
        'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-hover uk-table-bordered uk-margin-top'],
        'options'=> ['class' => 'items'],
        'layout' => '{items}{pager}<hr>',
        'pager' => ['class'=> 'worstinme\uikit\widgets\LinkPager'],
        'columns' => [
            [
                'attribute'=>'name',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->name,['update','id'=>$model->id]);
                },
            ],
            [
                'attribute'=>'type',
                'filter' => $searchModel->query()->select(['type'])->distinct()->indexBy('type')->column(),
            ],
            [
                'attribute'=>'position',
                'filter' => $searchModel->query()->select(['position'])->distinct()->indexBy('position')->column(),
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
</div>