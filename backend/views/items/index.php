<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::$app->controller->app->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];    
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="applications items-index">

    <div class="uk-grid uk-grid-small">

        <div class="uk-width-medium-5-6">

        <?php  \yii\widgets\Pjax::begin(['id'=>'catalog','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

        <?php  \yii\widgets\Pjax::begin(['id'=>'filter','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 
            <?= $this->render('_filter',['searchModel'=>$searchModel]); ?>
        <?php  \yii\widgets\Pjax::end(); ?>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-bordered uk-margin-top'],
            'options'=> ['class' => 'items'],
            'layout' => '{items}{pager}<hr>',
            'pager' => ['class'=> 'app\components\LinkPager'],
            'columns' => [
                
                [
                    'attribute'=>'search',
                    'label'=>'Наименование',
                    'format' => 'html',
                    'value' => function ($model, $index, $widget) {
                        return Html::a($model->name,['create','app'=>$model->app_id, 'id'=>$model->id]);
                    },
                    //'headerOptions'=>['class'=>'uk-text-center'],
                    //'contentOptions'=>['class'=>'uk-text-center'],
                ],
                [
                    'attribute'=>'category',
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
                //'user_id',
                //'flag',
                //'sort',
                // 'state',
                // 'created_at',
                // 'updated_at',
                // 'params:ntext',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{template}',
                    'buttons'=>[
                      'template' => function ($url, $model) {     
                        return Html::a('<i class="uk-icon-object-group"></i>', ['/'.Yii::$app->controller->module->id.'/default/templates','app'=>$model->app_id,'item'=>$model->id], [
                                'title' => Yii::t('backend', 'Настроить шаблон материала'),
                        ]);                                
                      },
                    ],
                    'contentOptions'=>['class'=>'uk-text-center'],                           
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{delete}',
                    'buttons'=>[
                      'delete' => function ($url, $model) {     
                        return Html::a('<i class="uk-icon-trash"></i>', $url, [
                                'title' => Yii::t('backend', 'Удалить'),
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

        <?php  \yii\widgets\Pjax::end(); ?>

        </div>

        <div class="uk-width-medium-1-6">
            <?=$this->render('/_nav')?>
        </div>

</div>

</div>