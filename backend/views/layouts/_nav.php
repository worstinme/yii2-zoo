<?php

use worstinme\uikit\Nav;
use worstinme\uikit\NavBar;
use yii\helpers\Html;

$app = Yii::$app->controller->app;

$subnav = [];

if (Yii::$app->controller->id == 'elements') {
    $subnav[] = ['label' => Yii::t('zoo', 'NAV_CREATE_ELEMENT'), 'url' => ['/zoo/elements/create', 'app' => $app->id]];
}

if (Yii::$app->controller->id == 'items') {
    $subnav[] = ['label' => Yii::t('zoo', 'NAV_CREATE_ITEM'), 'url' => ['/zoo/items/create', 'app' => $app->id]];
}

if (Yii::$app->controller->id == 'categories') {
    $subnav[] = ['label' => Yii::t('zoo', 'NAV_CREATE_CATEGORY'), 'url' => ['/zoo/categories/update', 'app' => $app->id]];
}

$mainnav = [
    ['label' => '<span class="uk-margin-small-right" uk-icon="icon: thumbnails"></span>' . $app->title,'url' => ['/zoo/applications/view', 'app' => $app->id]],
    ['label' => Yii::t('zoo', 'NAV_APPLICATION_ITEMS'),'url' => ['/zoo/items/index', 'app' => $app->id]],
    ['label' => Yii::t('zoo', 'NAV_APPLICATION_CATEGORIES'),'url' => ['/zoo/categories/index', 'app' => $app->id]],
    ['label' => Yii::t('zoo', 'NAV_APPLICATION_ELEMENTS'),'url' => ['/zoo/elements/index', 'app' => $app->id]],
];


if (isset($items) && is_array($items) && count($items)) {
    $subnav = array_merge($items, $subnav);
}

?>

<div class="subnav uk-navbar-container">
    <div class="uk-container uk-container-expand">
        <nav class="uk-navbar" uk-navbar>
            <div class="uk-navbar-left">

                <?= Nav::widget([
                    'navbar' => true,
                    'options' => ['data-uk-nav' => true],
                    'items' => $mainnav,
                ]); ?>


                <?= Nav::widget([
                    'navbar' => true,
                    'options' => ['data-uk-nav' => true],
                    'items' => $subnav,
                ]); ?>

            </div>

        </nav>
    </div>
</div>