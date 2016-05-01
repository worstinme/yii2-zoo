<?php

use yii\helpers\Html;

$this->title = $model->metaTitle;

$this->registerMetaTag(['name'=>'description', 'content'=> $model->metaDescription]);
$this->registerMetaTag(['name'=>'keywords', 'content'=> $model->metaDescription]);

$this->params['breadcrumbs'] = array_merge(isset($this->params['breadcrumbs'])?$this->params['breadcrumbs']:[],$model->breadcrumbs);

$rows = $model->getTemplateRows('full');

$class = !empty($row['params']) && !empty($row['params']['column'])?'uk-grid uk-grid-width-medium-1-'.$row['params']['column']:'';

?>
<div class="<?=$app->name?> <?=$app->name?>-item <?=$class?>">
<?php foreach ($rows as $row) {

        foreach ($row['items'] as $item) {
            
            if (!empty($item['element'])) {  ?>

            <div class="element element-<?=$item['element']?>">
                
            <?= $this->render('@worstinme/zoo/elements/'.$model->elements[$item['element']]['type'].'/view.php',[
                'model'=>$model,
                'attribute'=>$item['element'],
                'params'=>!empty($item['params'])?$item['params']:[],
            ]);?>       
                
            </div>
                
            <?php }

        }

} ?>
</div>