<?php echo "<?php"; ?>

use yii\helpers\Html;

$template = $model->getTemplate('teaser'); 

?>
<div class="item-teaser" data-item-id="<?="<?="?>$model->id?>"> 
<?="<?php"; ?> if (count($template)) {
    foreach ($template as $row) {
        if (count($row['items'])) {
            echo $this->render('@worstinme/zoo/frontend/views/default/rows/'.$row['type'],[
                'row'=>$row,
                'model'=>$model,
            ]);    
        }
    }
} ?>
</div>