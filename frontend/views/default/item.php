<?php

use worstinme\uikit\Breadcrumbs;

$this->title = $model->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $model->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $model->metaDescription]);

$template = $model->getTemplate('full'); 

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="uk-container uk-container-center">
<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
</div>

<div class="<?=$model->app->name?> <?=$model->app->name?>-item">
<div class="uk-container uk-container-center">
	
<?php if (count($template)) {
    foreach ($template as $row) {
        if (count($row['items'])) {
            echo $this->render('rows/'.$row['type'],[
                'row'=>$row,
                'model'=>$model,
            ]);    
        }
    }
} ?>

</div>
</div>