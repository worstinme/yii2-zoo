<?php

/* @var $this \yii\web\View */

/* @var $content string */

use worstinme\uikit\Alert;
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
                    'items' => array_filter([
                        ['label' => 'NAV_APPLICATIONS', 'url' => ['/zoo/applications/index'],
                            'items'=> \yii\helpers\ArrayHelper::toArray(Yii::$app->zoo->applications,[
                                'worstinme\zoo\Application' => [
                                    'label'=>'title',
                                    'url'=>function ($app) {
                                        return ['/zoo/items/index','app'=>$app->id];
                                    },
                                ],
                            ]),],
                        Yii::$app->has('widgets') ? ['label' => 'Виджеты', 'url' => ['widgets/index']] : null,
                        ['label' => 'NAV_ELFINDER', 'url' => ['/zoo/elfinder/index']],
                        ['label' => 'NAV_MENU', 'url' => ['/zoo/menu/index']],
                        ['label' => 'NAV_CONFIG', 'url' => ['/zoo/config/index']],
                    ]),
                ]); ?>
            </div>
            <div class="uk-navbar-right">
                <?= Menu::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'uk-navbar-nav uk-hidden-small'],
                    'items' => [
                        ['label' => '<i class="uk-icon-home"></i>', 'encodeLabels' => false, 'url' => '/', 'linkOptions' => ['target' => '_blank']],
                        Yii::$app->user->isGuest ?
                            ['label' => 'Войти', 'url' => ['/user/default/login'],
                                'items' => [
                                    ['label' => 'Зарегистрироваться', 'url' => ['/user/default/signup'],]
                                ]
                            ] :
                            ['label' => Yii::$app->user->identity->username, 'url' => ['/user/default/update'],
                                /* 'items' => [
                                     ['label' => 'Выйти',
                                         'url' => ['/user/default/logout'],
                                         'linkOptions' => ['data-method' => 'post'],]
                                 ] */
                            ],
                    ],
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
