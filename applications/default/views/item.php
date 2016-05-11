<?php

use yii\helpers\Html;

$this->title = $model->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $model->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $model->metaDescription]);

$this->params['breadcrumbs'] = array_merge(isset($this->params['breadcrumbs'])?$this->params['breadcrumbs']:[],$model->breadcrumbs);

$class = !empty($row['params']) && !empty($row['params']['column'])?'uk-grid uk-grid-width-medium-1-'.$row['params']['column']:'';

?>
<article class="<?=$app->name?> <?=$app->name?>-item <?=$class?>">

<?=\worstinme\zoo\helpers\TemplateHelper::render($model,'full')?>

</article>