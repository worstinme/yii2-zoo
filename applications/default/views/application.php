<?php echo "<?php"; ?>

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use worstinme\uikit\Breadcrumbs;

$this->title = $app->title;
$this->params['breadcrumbs'][] = $this->title;

if(count(Yii::$app->request->get())) $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);

?>

<div class="<?=$controller?> <?=$controller?>-application">
    <?php echo "<?php /*"; ?><div class="uk-grid">

        <div class="uk-width-medium-1-4">
            <?="<?="; ?>$this->render('_search', ['app' => $app,'searchModel'=>$searchModel]); ?>
        </div>

        <div class="uk-width-medium-3-4"> ?> */ ?>

            <h1><?="<?="; ?> Html::encode($this->title) ?></h1>

            <?="<?="; ?>$app->intro?>

            <?="<?="; ?> ListView::widget([
                'dataProvider' => $dataProvider,
                'layout'=>'<div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div><div class="uk-grid uk-grid-medium items" data-uk-grid-match=\'{"target":".zoo-item-teaser"}\'>{items}</div><div class="catalog-nav"><div class="sorter">Упорядочить по {sorter}</div><div class="pager">{pager}</div></div>',
                'itemOptions' => ['class' => 'item uk-grid-margin uk-width-1-2 uk-width-medium-1-3'],
                'itemView' => '_teaser',
            ]) ?>

            <?="<?="; ?>$app->content?>

    <?php echo "<?php /*"; ?></div> 

    </div> */ ?>

</div>