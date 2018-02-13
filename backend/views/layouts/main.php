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

    <div class="zoo">
        <div class="mainnav uk-navbar-container">
            <div class="uk-container uk-container-expand">
                <nav class="uk-navbar" uk-navbar>
                    <div class="uk-navbar-left">
                        <?= Menu::widget([
                            'options' => ['class' => 'uk-navbar-nav uk-hidden-small'],
                            'activeCssClass' => 'uk-active',
                            'submenuTemplate' => "\n<div class=\"uk-navbar-dropdown\">\n<ul class=\"uk-nav uk-navbar-dropdown-nav\">\n{items}\n</ul>\n</div>\n",
                            'items' => array_filter([
                                ['label' => Yii::t('zoo','NAV_APPLICATIONS'), 'url' => ['/zoo/applications/index'],
                                    'items' => \yii\helpers\ArrayHelper::toArray(Yii::$app->zoo->applications, [
                                        'worstinme\zoo\Application' => [
                                            'label' => 'title',
                                            'url' => function ($app) {
                                                return ['/zoo/items/index', 'app' => $app->id];
                                            },
                                        ],
                                    ]),],['label' => Yii::t('zoo','NAV_ELFINDER'), 'url' => ['/zoo/elfinder/index']],
                                ['label' => Yii::t('zoo','NAV_MENU'), 'url' => ['/zoo/menu/index']],
                                ['label' => Yii::t('zoo','NAV_CONFIG'), 'url' => ['/zoo/config/index']],
                            ]),
                        ]); ?>
                    </div>
                </nav>
            </div>
        </div>

        <?php if (isset(Yii::$app->controller->subnav) && Yii::$app->controller->subnav) : ?>
            <?= $this->render('_nav', [
            ]) ?>
        <?php endif; ?>

        <section id="content" class="uk-container uk-container-expand uk-margin-top">
            <?= Alert::widget() ?>
            <?= $content ?>
        </section>

    </div>

<?php $this->endContent(); ?>