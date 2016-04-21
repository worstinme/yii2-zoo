<?php echo "<?php"; ?>

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;

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

$layout = $app->itemsColumns > 1 ? '<div class="uk-grid uk-grid-width-medium-1-'.$app->itemsColumns.' uk-grid-match items" data-uk-grid-margin>{items}</div>' : '<div class="items">{items}</div><div class="pager">{pager}</div>';

if ($app->itemsSort) {
    $layout = '<div class="sorter">Упорядочить по {sorter}</div>'.$layout;
}


?>
<div class="<?=$controller?> <?=$controller?>-category">

<?="<?php"; ?>  \yii\widgets\Pjax::begin(['id'=>'catalog','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

    <?php echo "<?php /*"; ?><div class="uk-grid">

        <div class="uk-width-medium-1-4">
            <?="<?="; ?> $this->render('_search', ['app' => $app,'searchModel'=>$searchModel]); ?>
        </div>

        <div class="uk-width-medium-3-4"> */ ?>

            <h1><?="<?="; ?> Html::encode($category->name) ?></h1>

			<?="<?="; ?> $category->intro?>

            <div class="uk-grid uk-grid-width-medium-1-3">
            <?="<?php"; ?> foreach ($category->related as $related): ?>
                <div><?="<?="; ?> Html::a($related->name, $related->url); ?></div>
            <?="<?php"; ?> endforeach ?>
            </div>

            <?="<?="; ?> ListView::widget([
                'dataProvider' => $dataProvider,
                'layout'=>$layout,
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_teaser',
            ]) ?>

            <?="<?="; ?> $category->content?>

        <?php echo "<?php /*"; ?></div>

    </div> */ ?>

<?="<?php"; ?> \yii\widgets\Pjax::end(); ?>
</div>

<?="<?php"; ?>

$js = <<<JS
$("#catalog").on("pjax:start",function(){ $(this).addClass("reload");$(this).find(".submit-button").attr("disabled","disabled")});
$("#catalog").on("pjax:end",function(){ $(this).removeClass("reload");})
JS;

$this->registerJs($js, $this::POS_READY); ?>