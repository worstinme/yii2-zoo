<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('backend', 'Menu');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications items-index">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">
 
    <div class="items">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-bordered'],
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
                'attribute'=>'label',
                'label'=>'Наименование',
                'format' => 'html',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->label,['update', 'menu'=>$model->id]);
                },
                //'headerOptions'=>['class'=>'uk-text-center'],
                //'contentOptions'=>['class'=>'uk-text-center'],
            ],
            'application_id',
            'category_id',
            'item_id',
            // 'class',
            // 'parent_id',
            // 'sort',
            'type',
            'menu',
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{delete}',
                    'buttons'=>[
                      'delete' => function ($url, $model) {     
                        return Html::a('<i class="uk-icon-trash"></i>', $url, [
                                'title' => Yii::t('backend', 'Удалить'),
                        ]);                                
                      },
                    ],
                    'contentOptions'=>['class'=>'uk-text-center'],                           
                ],
        ],
    ]); ?>
    
    </div>

    </div>

    <div class="uk-width-medium-1-5">
    <?= Html::a(Yii::t('backend','Добавить пункт меню'), ['/'.Yii::$app->controller->module->id.'/menu/update'], ['class' => 'uk-button uk-button-success']); ?>

    </div>


</div>

</div>
