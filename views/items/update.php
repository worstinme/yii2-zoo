<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model worstinme\zoo\models\Items */

$this->title = Yii::t('admin', 'Update {modelClass}: ', [
    'modelClass' => 'Items',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Update');
?>
<div class="items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
