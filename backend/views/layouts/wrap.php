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

<?= $content?>

<section class="uk-container uk-container-expand uk-margin-top">
    <?= Breadcrumbs::widget(['homeLink' => ['label' => 'Админка', 'url' => ['/zooadmin/default/index'],], 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
