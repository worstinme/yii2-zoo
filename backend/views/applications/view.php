<?php

use yii\helpers\Html;

$this->title = Yii::t('zoo', 'APPLICATIONS_VIEW_TITLE');
$this->params['breadcrumbs'][] = $this->title;

?>

<h2><?= Yii::t('zoo', 'APPLICATIONS_CONTENTS_TITLE') ?></h2>

<ul class="uk-subnav uk-subnav-divider">
<?php foreach (Yii::$app->zoo->languages as $lang => $language) : ?>

    <li><?= Html::a($language, ['update', 'app' => $app->id, 'lang' => $lang]) ?></li>

<?php endforeach; ?>
</ul>
