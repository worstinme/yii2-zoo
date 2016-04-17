<?php echo "<?php"; ?>

use worstinme\uikit\Breadcrumbs;

$this->title = $model->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $model->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $model->metaDescription]);

$template = $model->getTemplate('full'); 

if ($model->parentCategory !== null) {
    $this->params['breadcrumbs'][] = ['label' => $model->parentCategory->name, 'url' =>  $model->parentCategory->url]; 
}

$this->params['breadcrumbs'][] = $this->title;


?>

<div class="<?=$controller?> <?=$controller?>-item">
	
<?php echo "<?php"; ?> if (count($template)) {
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