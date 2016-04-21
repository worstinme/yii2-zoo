<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">


    <p>
        <?= Html::a('Произвольный параметр', ['create'], ['class' => 'uk-button uk-button-primary']) ?>
    </p>
    
    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summaryOptions'=>['class'=>'uk-text-center'],
                'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-hover uk-table-bordered uk-margin-top'],
                'options'=> ['class' => 'items'],
                'layout' => '{items}{pager}<hr>',
                'pager' => ['class'=> 'worstinme\uikit\widgets\LinkPager'],
                'columns' => [
                    'id',
                    [
                        'attribute'=>'name',
                        'label'=>'Наименование',
                        'format' => 'raw',
                        'value' => function ($model, $index, $widget) {
                            return Html::a($model->name,['update','id'=>$model->id,'name'=>$model->name]);
                        },
                    ],
                    'value',
                    'comment',
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
