<?php

use yii\helpers\Html;


?>

<form>

<?= Html::checkbox('preview', isset($params['preview']) ? $params['preview'] : null,['label'=>'Отобразить превью первой картинки']); ?><br>
<?= Html::checkbox('asUrl', isset($params['asUrl']) ? $params['asUrl'] : null,['label'=>'Отображать ссылку на материал?']); ?><br>
<?= Html::textInput('width', isset($params['width']) ? $params['width'] : null, ['placeholder' => 'Ширина']); ?><br>
<?= Html::textInput('height', isset($params['height']) ? $params['height'] : null, ['placeholder' => 'Высота']); ?><br>
<?= Html::checkbox('lightbox', isset($params['lightbox']) ? $params['lightbox'] : null, ['label' => 'lightbox']); ?><br>

<?= Html::dropDownList('type',isset($params['type']) ? $params['type'] : null, 
	['gallery'=>'Галлерея','wall'=>'Список','slideshow'=>'Slideshow'], ['prompt' => 'выбрать тип отображения']); ?>

</form>