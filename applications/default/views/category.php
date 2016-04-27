<?php

use yii\helpers\Html;
use yii\widgets\ListView;

if(count(Yii::$app->request->get())) $this->registerLinkTag(['rel' => 'canonical', 'href' => \yii\helpers\Url::canonical()]);

$this->title = $category->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $category->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $category->metaDescription]);

$this->params['breadcrumbs'] = array_merge(isset($this->params['breadcrumbs'])?$this->params['breadcrumbs']:[],$category->breadcrumbs);

$layout = $app->itemsColumns > 1 ? '<div class="uk-grid uk-grid-width-medium-1-'.$app->itemsColumns.' uk-grid-match items" data-uk-grid-margin>{items}</div>' : '<div class="items">{items}</div><div class="pager">{pager}</div>';

if ($app->itemsSort) $layout = '<div class="sorter">Упорядочить по {sorter}</div>'.$layout;

$pjaxId = $app->name.'-category';

?>
<div class="<?=$app->name?> <?=$app->name?>-category">
<?php \yii\widgets\Pjax::begin(['id'=>$pjaxId,'timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

    <h1><?= Html::encode($category->name) ?></h1>

	<?= $category->intro?>

    <div class="uk-grid uk-grid-width-medium-1-3">
    <? foreach ($category->related as $related): ?>
        <div><?=  Html::a($related->name, $related->url); ?></div>
    <? endforeach ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>$layout,
        'itemOptions' => ['tag' => false],
        'itemView' => '_teaser',
    ]) ?>

    <?= $category->content?>

<?php \yii\widgets\Pjax::end(); ?>
</div>

<?php $js = <<<JS
$("#$pjaxId").on("pjax:start",function(){ $(this).addClass("reload");});
$("#$pjaxId").on("pjax:end",function(){ $(this).removeClass("reload");})
JS;
$this->registerJs($js, $this::POS_READY); ?>
