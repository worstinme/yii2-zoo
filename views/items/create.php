<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model worstinme\zoo\models\Items */

$this->title = Yii::t('admin', 'Create Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
