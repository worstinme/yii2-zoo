<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use worstinme\uikit\Breadcrumbs;

if(count(Yii::$app->request->get())) $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);

$this->title = $category->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $category->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $category->metaDescription]);

$this->params['breadcrumbs'][] = ['label' => $app->title, 'url' => $app->url];
if ($category->parent !== null) {
    $this->params['breadcrumbs'][] = ['label' => $category->parent->name, 'url' =>  $category->parent->url]; 
    if ($category->parent->parent !== null) {
        $this->params['breadcrumbs'][] = ['label' => $category->parent->parent->name, 'url' =>  $category->parent->parent->url]; 
    }
}
$this->params['breadcrumbs'][] = $category->name;

?>
<div class="<?=$app->name?> <?=$app->name?>-category">
<div class="uk-container uk-container-center">

<?php  \yii\widgets\Pjax::begin(['id'=>'catalog','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

    <div class="uk-grid">

        <div class="uk-width-medium-1-4">
            <?php echo $this->render('@worstinme/zoo/frontend/views/default/_search', ['app' => $app,'searchModel'=>$searchModel]); ?>
        </div>

        <div class="uk-width-medium-3-4">

            <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>

            <h1><?= Html::encode($category->name) ?></h1>

			<?=$category->preContent?>

            <div class="uk-grid uk-grid-width-medium-1-3">
            <?php foreach ($category->related as $related): ?>
                <div><?= Html::a($related->name, $related->url); ?></div>
            <?php endforeach ?>
            </div>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'summary'=>'Подобрано {totalCount} из '.$searchModel->query()->count().' показаны с {begin} по {end}',
                'layout'=>'<div class="spinner"></div><p class="summary">{summary}</p><div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div><div class="items uk-grid uk-grid-medium uk-grid-match uk-grid-width-1-1 uk-grid-width-medium-1-3">{items}</div><div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div>',
                'itemOptions' => ['class' => 'item uk-grid-margin'],
                'itemView' => '@worstinme/zoo/frontend/views/default/_teaser',
            ]) ?>

            <?=$category->content?>

        </div>

    </div>

<?php \yii\widgets\Pjax::end(); ?>
</div>
</div>

<?php

$js = <<<JS
$("#catalog").on("pjax:start",function(){ $(this).addClass("reload");$(this).find(".submit-button").attr("disabled","disabled")});
$("#catalog").on("pjax:end",function(){ $(this).removeClass("reload");})
JS;

$this->registerJs($js, $this::POS_READY); ?>