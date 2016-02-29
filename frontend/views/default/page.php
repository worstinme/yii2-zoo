<?php

$this->title = $page->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $page->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $page->metaDescription]);

$template = $page->getTemplate('full'); 


?>

<div class="zoo-default-index"> 
	
<?php if (count($template)) {
    foreach ($template as $row) {
        if (count($row['items'])) {
            echo $this->render('rows/'.$row['type'],[
                'row'=>$row,
                'page'=>$page,
            ]);    
        }
    }
} ?>

</div>
