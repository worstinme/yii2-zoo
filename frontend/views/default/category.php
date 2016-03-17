<?php

use worstinme\uikit\Breadcrumbs;

$this->title = $category->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $category->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $category->metaDescription]);



$this->params['breadcrumbs'][] = $this->title;

?>

<div class="uk-container uk-container-center">
<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
</div>

<div class="<?=$category->app->name?> <?=$category->app->name?>-category">
<div class="uk-container uk-container-center">
	
<?=$category->preContent?>
<hr>
<?=$category->content?>

</div>
</div>