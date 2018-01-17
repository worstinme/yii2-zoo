<?php

use yii\helpers\Html;


?>


<?= Html::activeDropDownList($model, $element->attributeName, Yii::$app->zoo->languages,['class'=>'uk-select']); ?>