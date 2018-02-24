<?php

/* @var $this \yii\web\View */

/* @var $content string */

use worstinme\uikit\Breadcrumbs;
use worstinme\uikit\Nav;
use yii\helpers\Html;
use yii\widgets\Menu;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="admin">

<?php $this->beginBody() ?>

<div class="mainnav uk-navbar-container">
    <div class="uk-container uk-container-expand">
        <nav class="uk-navbar" uk-navbar>
            <div class="uk-navbar-left">
                <?= Menu::widget([
                    'options' => ['class' => 'uk-navbar-nav uk-hidden-small'],
                    'activeCssClass' => 'uk-active',
                    'submenuTemplate' => "\n<div class=\"uk-navbar-dropdown\">\n<ul class=\"uk-nav uk-navbar-dropdown-nav\">\n{items}\n</ul>\n</div>\n",
                    'items' => Yii::$app->getModule('zoo')->nav,
                ]); ?>
            </div>
        </nav>
    </div>
</div>

<?= $content?>

<section class="uk-container uk-container-expand uk-margin-top">
    <?= Breadcrumbs::widget(['homeLink' => ['label' => 'Админка', 'url' => ['/zooadmin/default/index'],], 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
