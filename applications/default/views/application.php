<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use worstinme\uikit\Breadcrumbs;

$this->title = $app->metaTitle;
$this->params['breadcrumbs'][] = $app->title;

if(count(Yii::$app->request->get())) $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);

$layout = $app->itemsColumns > 1 ? '<div class="uk-grid uk-grid-width-medium-1-'.$app->itemsColumns.' uk-grid-match items" data-uk-grid-margin>{items}</div>' : '<div class="items">{items}</div><div class="pager">{pager}</div>';

if ($app->itemsSort) {
    $layout = '<div class="sorter">Упорядочить по {sorter}</div>'.$layout;
}

$pjaxId = $app->name.'-application';

?>

<div class="<?=$app->name?> <?=$app->name?>-application">
<?php \yii\widgets\Pjax::begin(['id'=>$pjaxId,'timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

    <h1><?= Html::encode($app->title) ?></h1>

    <?= $app->intro?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>$layout,
        'itemOptions' => ['tag' => false],
        'itemView' => '_teaser',
    ]) ?>

    <?= $app->content?>

<?php \yii\widgets\Pjax::end(); ?>
</div>

<?php $js = <<<JS
$("#$pjaxId").on("pjax:start",function(){ $(this).addClass("reload");});
$("#$pjaxId").on("pjax:end",function(){ $(this).removeClass("reload");})
JS;
$this->registerJs($js, $this::POS_READY); ?>
