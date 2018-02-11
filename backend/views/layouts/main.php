<?php

/* @var $this \yii\web\View */

/* @var $content string */

use worstinme\uikit\Alert;
use worstinme\uikit\Breadcrumbs;
use worstinme\uikit\Nav;
use yii\helpers\Html;
use yii\widgets\Menu;

\worstinme\zoo\backend\assets\AdminAsset::register($this);

$this->beginContent(Yii::$app->zoo->backendLayout); ?>

<?php if (isset(Yii::$app->controller->subnav) && Yii::$app->controller->subnav) : ?>
    <?= $this->render('_nav',[
    ]) ?>
<?php endif; ?>

<section id="content" class="uk-container uk-container-expand uk-margin-top">
    <?= Alert::widget() ?>
    <?= $content ?>
</section>

<?php $this->endContent(); ?>