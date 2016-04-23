<?php echo "<?php"; ?>

use yii\helpers\Html;

$rows = $model->getTemplateRows('teaser');

?>
<div class="item-teaser" data-item-id="<?php echo "<?="; ?>$model->id?>"> 
<?php echo "<?php"; ?> foreach ($rows as $row) {

		$class = !empty($row['params']) && !empty($row['params']['column'])?'uk-grid uk-grid-width-medium-1-'.$row['params']['column']:'row';


		foreach ($row['items'] as $item) {
			
			if (!empty($item['element'])) {  ?>

			<div class="element element-<?php echo "<?="; ?>$item['element']?>">
				
			<?php echo "<?="; ?>$this->render('@worstinme/zoo/elements/'.$model->elements[$item['element']]['type'].'/_view.php',[
	            'model'=>$model,
	            'attribute'=>$item['element'],
	            'params'=>!empty($item['params'])?$item['params']:[],
	        ]);?>		
				
			</div>
				
			<?php echo "<?php"; ?> }

		}

} ?>
</div>