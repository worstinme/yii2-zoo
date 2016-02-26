<?php

$this->title = $page->name;

$template = $page->app->getTemplate('full'); ?>

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
