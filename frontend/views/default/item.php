<?php

$this->title = $model->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $model->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $model->metaDescription]);

$template = $model->getTemplate('full'); 

?>

<div class="zoo-default-index"> 
	
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
