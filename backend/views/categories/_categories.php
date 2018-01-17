<?php
//
use yii\helpers\Html;

$related = isset($related) ? $related : false;

?>
<ul class="nestable <?=$related?'related':'parent'?>  parent-<?= $parent_id ?>" data-parent-id="<?= $parent_id ?>" uk-sortable="group: group; handle: .uk-sortable-handle" <?=$related?'hidden':''?>>
    <?php if (count($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <li class="uk-nestable-item" data-item-id="<?= $category->id ?>">
                <div class="nestable-panel">
                  <!--  <span class="uk-sortable-handle uk-margin-small-right" uk-icon="icon: table"></span> -->
                    <span class="parent-<?= $category->id ?> uk-margin-small-right" uk-icon="icon: plus" uk-toggle="target: .parent-<?= $category->id ?>"></span>
                    <span class="parent-<?= $category->id ?> uk-margin-small-right" uk-icon="icon: minus" uk-toggle="target: .parent-<?= $category->id ?>" hidden></span>
                    #<?= $category->id ?>
                    <?= Html::a($category->name, ['update', 'app' => $category->app_id, 'category' => $category->id]) ?>
                    / <?= $category->alias ?>

                    <?= Html::a('<i uk-icon="icon: check"></i>', '#', ['onClick' => "var link=$(this);$.ajax({url:'" . \yii\helpers\Url::to(['update', 'app' => $category->app_id, 'category' => $category->id]) . "',type:'POST',data: {'" . $category->formName() . "[state]':0},success: function(data){ if(data.success) { $('.state-button-$category->id').toggleClass('uk-hidden'); } }})", 'class' => "uk-margin-large-left state-button-".$category->id.($category->state ? null : " uk-hidden")]) ?>
                    <?= Html::a('<i uk-icon="icon: close"></i>', '#', ['onClick' => "var link=$(this);$.ajax({url:'" . \yii\helpers\Url::to(['update', 'app' => $category->app_id, 'category' => $category->id]) . "',type:'POST',data: {'" . $category->formName() . "[state]':1},success: function(data){ if(data.success) { $('.state-button-$category->id').toggleClass('uk-hidden'); } }})", 'class' => "uk-margin-large-left state-button-".$category->id.($category->state ? " uk-hidden" : null)]) ?>

                    <?= Html::a('<i uk-icon="icon: trash"></i>', ['delete', 'app' => Yii::$app->controller->app->id, 'category' => $category->id], [
                        'class' => 'uk-float-right',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Уверены что хотите удалить категорию ' . $category->name . '?',
                        ],
                    ]) ?>


                    <i class="uk-float-right uk-margin-right"></i>
                </div>

                <?= $this->render('_categories', [
                    'categories' => $category->related,
                    'related' => true,
                    'parent_id' => $category->id,
                ]) ?>
            </li>
        <?php endforeach ?>
    <?php endif ?>
</ul>