<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use worstinme\uikit\Nav;
use worstinme\uikit\NavBar;
use worstinme\uikit\Alert;
use worstinme\uikit\Breadcrumbs;
use yii\helpers\ArrayHelper;

\worstinme\zoo\assets\AdminAsset::register($this);

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

<?php NavBar::begin(['container'=>true,'offcanvas'=>true]); ?>
    
    <?= Nav::widget([
        'navbar'=>true,
        'options'=>['class'=>'uk-hidden-small'],
        'items' => [
            ['label' => '<i class="uk-icon-bars"></i> Приложения', 'url' => ['/zooadmin/default/index'],
                'items'=> ArrayHelper::toArray(Yii::$app->zoo->applications,[
                    'worstinme\zoo\models\Applications' => [
                        'label'=>'title',
                        'url'=>function ($app) {
                            return ['/zooadmin/items/index','app'=>$app->id];
                        },
                    ],
                ]),
            ], 
            ['label' => 'Виджеты','encodeLabels'=>false, 'url' => ['/zooadmin/widgets/index'],], 
            ['label' => 'Настройки','encodeLabels'=>false, 'url' => ['/zooadmin/config/index'],],
            ['label' => 'Файлы','encodeLabels'=>false, 'url' => ['/zooadmin/elfinder/index']],
            ['label' => 'Меню', 'url' => ['/zooadmin/menu/index']], 
            ['label' => 'Пользователи','encodeLabels'=>false, 'url' => ['/useradmin/default/index'],], 

        ],
    ]);?>
    
    <div class="uk-navbar-flip">

        <?= Nav::widget([
            'navbar'=>true,
            'options'=>['class'=>'uk-hidden-small'],
            'items' => [    
                ['label'=>'<i class="uk-icon-home"></i>','encodeLabels'=>false,'url'=>'/','linkOptions'=>['target'=>'_blank']], 
                Yii::$app->user->isGuest ?
                    ['label' => 'Войти', 'url' => ['/user/default/login'],
                        'items'=>[
                            ['label' => 'Зарегистрироваться', 'url' => ['/user/default/signup'],]
                        ]
                    ] :
                    ['label' =>Yii::$app->user->identity->username,'url' => ['/user/profile/index'],
                        'items'=>[
                            ['label' => 'Выйти',
                                                    'url' => ['/user/default/logout'],
                                                    'linkOptions' => ['data-method' => 'post'],]
                        ]
                    ],
            ],
        ]); ?>

    </div>

<?php NavBar::end(); ?>

<section id="content" class="uk-container uk-container-center uk-margin-top">  
    <?= Alert::widget() ?>
    <?= $content ?>
</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
