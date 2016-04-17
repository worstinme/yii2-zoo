<?php echo "<?php"; ?>

use yii\helpers\Html;

$template = $model->getTemplate('teaser'); 

?>
<div class="item-teaser" data-item-id="<?="<?="?>$model->id?>"> 
<?="<?php"; ?> $items = []; foreach ($model->renderedElements as $attribute) {

    if (!empty($model->$attribute)) {
        $items[$attribute] = $this->render('@worstinme/zoo/elements/'.$model->elements[$attribute]['type'].'/_view.php',[
            'model'=>$model,
            'attribute'=>$attribute,
        ]);
    }
    
}
echo $this->render($model->getRendererView('teaser'), [
    'items'=>$items,
    'rows'=>$model->getTemplateRows('teaser'),
]); ?>
</div>