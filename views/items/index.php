<?php

use yii\helpers\Html;
use worstinme\uikit\GridView; 

/* @var $this yii\web\View */
/* @var $searchModel worstinme\zoo\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin', 'Материалы');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => ['/'.Yii::$app->controller->module->id.'/default/application','app'=>$app->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications items-index">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

    <hr>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('<i class="uk-icon-plus"></i> '.Yii::t('admin','Создать материал'),
                    ['/'.Yii::$app->controller->module->id.'/items/create','app'=>$app->id],
                    ['class' => 'uk-button uk-button-success'])?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'state',
            // 'created_at',
            // 'updated_at',
            // 'params:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    </div>

    <div class="uk-width-medium-1-5">
        <?=$this->render('/_nav',['app'=>$app])?>
    </div>

</div>

</div>