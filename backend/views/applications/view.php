<?php

use yii\helpers\Html;

$this->title = Yii::t('zoo', 'APPLICATIONS_VIEW_TITLE');

?>

<h1><?=$app->title?></h1>

<h2><?= Yii::t('zoo', 'APPLICATIONS_VIEW_LANGUAGES_TITLE') ?></h2>

<ul class="uk-subnav uk-subnav-divider">
<?php foreach (Yii::$app->zoo->languages as $lang => $language) : ?>

    <li><?= Html::a($language, ['update', 'app' => $app->id, 'lang' => $lang]) ?></li>

<?php endforeach; ?>
</ul>
