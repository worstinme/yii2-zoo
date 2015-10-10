<?php

use yii\helpers\Html;

$this->title = $app->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin','Приложения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $app->title;

?>



<div class="applications">

<div class="uk-grid">
	
	<div class="uk-width-medium-4-5">

		<div class="uk-panel uk-panel-box">
		<p>TODO: Список материалов</p>
		</div>

	</div>

	<div class="uk-width-medium-1-5">
		<?=$this->render('_nav',['app'=>$app])?>
	</div>

</div>

</div>