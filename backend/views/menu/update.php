<?php

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Yii::t('zoo', 'MENU_CREATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('zoo','MENU_INDEX_BREADCRUMBS'), 'url' => ['/zoo/menu/index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications items-create">

<div class="uk-grid">
    
    <div class="uk-width-medium-4-5">

        <div class="uk-panel-box">

        <?= $this->render('_form', [
            'model' => $model,
            'categories' => $categories,
            'applications'=>$applications,
            'items'=>$items,
        ]) ?>

        </div>

	</div>


</div>

</div>
