<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use worstinme\uikit\Breadcrumbs;

$this->title = $app->title;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="<?=$app->name?>">
<div class="uk-container uk-container-center">
    <div class="uk-grid">

        <div class="uk-width-medium-1-4">
            <?php echo $this->render('@worstinme/zoo/frontend/views/default/_search', ['app' => $app,'searchModel'=>$searchModel]); ?>
        </div>

        <div class="uk-width-medium-3-4">

            <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>

            <h1><?= Html::encode($this->title) ?></h1>

            <?=$app->frontpage?>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout'=>'<div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div><div class="uk-grid uk-grid-medium items" data-uk-grid-match=\'{"target":".zoo-item-teaser"}\'>{items}</div><div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div>',
                'itemOptions' => ['class' => 'item uk-grid-margin uk-width-1-2 uk-width-medium-1-3'],
                'itemView' => '@worstinme/zoo/frontend/views/default/_teaser',
            ]) ?>

        </div>

    </div>
</div>
</div>