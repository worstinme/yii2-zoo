<?php echo "<?php"; ?>

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
	
<?php echo "<?php"; ?> $items = []; foreach ($model->renderedElements as $attribute) {

    if (!empty($model->$attribute)) {
        $items[$attribute] = $this->render('@worstinme/zoo/elements/'.$model->elements[$attribute]['type'].'/_view.php',[
            'model'=>$model,
            'attribute'=>$attribute,
        ]);
    }
    
}
echo $this->render($model->getRendererView('full'), [
    'items'=>$items,
    'rows'=>$model->getTemplateRows('full'),
]); ?>

</div>