<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use worstinme\uikit\Breadcrumbs;

$this->title = $app->title;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="<?=$app->name?>">
<div class="uk-container uk-container-center">

<?php  \yii\widgets\Pjax::begin(['id'=>'catalog','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 



        <div class="extended-search">
            <?php echo $this->render('@worstinme/zoo/frontend/views/default/_extended_search', ['app' => $app,'searchModel'=>$searchModel]); ?>
        </div>


        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>

        <h1><?= Html::encode($this->title) ?></h1>

        <?=$app->frontpage?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'summary'=>'Подобрано {totalCount} из '.$searchModel->query()->count().' показаны с {begin} по {end}',
            'layout'=>'<div class="spinner"></div><p class="summary">{summary}</p><div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div><div class="items uk-grid uk-grid-medium uk-grid-match uk-grid-width-1-1 uk-grid-width-medium-1-3">{items}</div><div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div>',
            'itemOptions' => ['class' => 'item uk-grid-margin'],
            'itemView' => '@worstinme/zoo/frontend/views/default/_teaser',
        ]) ?>


<?php  \yii\widgets\Pjax::end(); ?>
</div>
</div>

<?php

$js = <<<JS
$("#catalog").on("pjax:start",function(){ $(this).addClass("reload");$(this).find(".submit-button").attr("disabled","disabled")});
$("#catalog").on("pjax:end",function(){ $(this).removeClass("reload");})
JS;

$this->registerJs($js, $this::POS_READY); ?>