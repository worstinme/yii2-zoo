<?php echo "<?php"; ?>

use yii\helpers\Html;

$this->title = $model->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $model->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $model->metaDescription]);

if ($model->parentCategory !== null) {
    $this->params['breadcrumbs'][] = ['label' => $model->parentCategory->name, 'url' =>  $model->parentCategory->url]; 
}

$this->params['breadcrumbs'][] = $this->title;

$rows = $model->getTemplateRows('full');

?>

<div class="<?=$controller?> <?=$controller?>-item">
	
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