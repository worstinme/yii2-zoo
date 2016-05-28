<?php

use yii\helpers\Html; 

$width = isset($params['width']) ? (int)$params['width'] : null;
$height = isset($params['height']) ? (int)$params['height'] : null;

if (!empty($model->$attribute)) {
	
preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $model->$attribute, $matches);

?>

<iframe width="<?=$width?>" height="<?=$height?>" class="uk-responsive-width" src="https://www.youtube.com/embed/<?=$matches[1]?>" frameborder="0" allowfullscreen></iframe>

<?php

}