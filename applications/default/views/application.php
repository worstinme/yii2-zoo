<?php echo "<?php"; ?>

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

?>

<div class="<?=$controller?> <?=$controller?>-application">
    <?php echo "<?php /*"; ?><div class="uk-grid">

        <div class="uk-width-medium-1-4">
            <?="<?="; ?>$this->render('_search', ['app' => $app,'searchModel'=>$searchModel]); ?>
        </div>

        <div class="uk-width-medium-3-4"> ?> */ ?>

            <h1><?="<?="; ?> Html::encode($app->title) ?></h1>

            <?="<?="; ?>$app->intro?>

            <?="<?="; ?> ListView::widget([
                'dataProvider' => $dataProvider,
                'layout'=>$layout,
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_teaser',
            ]) ?>

            <?="<?="; ?>$app->content?>

    <?php echo "<?php /*"; ?></div> 

    </div> */ ?>

</div>
