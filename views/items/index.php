<?php

use yii\helpers\Html;
use worstinme\uikit\GridView; 

/* @var $this yii\web\View */
/* @var $searchModel worstinme\zoo\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin', 'Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['/'.Yii::$app->controller->module->id.'/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-index">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">


    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('admin', 'Create Items'), ['create'], ['class' => 'btn btn-success']) ?>
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
        <?=$this->render('/default/_nav',['app'=>$app])?>
    </div>

</div>

</div>