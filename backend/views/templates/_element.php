<?php

use yii\helpers\Html;

$id = $element->name.uniqid();

$items = !empty($items) ? $items : [];

$elements = Yii::$app->controller->app->elements;

?><li class="uk-nestable-item<?= count($items)?' uk-parent':''?>" data-element="<?=$element->name?>">
    <div class="uk-nestable-panel">
        <i class="uk-nestable-handle uk-icon uk-icon-bars"></i>
        <?=$element->label?>
        
        <?php if (!empty($element->paramsView)): ?>
        <a class="uk-icon-plus" data-uk-toggle="{target:'#<?=$id?>'}"></a>
        <div class="params uk-hidden" id="<?=$id?>">
            <?=$this->render($element->paramsView,[
                'element'=>$element,
                'params'=>$params,
            ])?>
        </div>
        <?php endif ?>
    </div>
    <?php if (count($items)): ?>
        <ul class="uk-nestable-list">
            <?php foreach ($items as $item) {
                if (!empty($item['element']) && isset($elements[$item['element']])) {
                    echo $this->render('_element',[
                        'items'=>[],
                        'element'=>$elements[$item['element']],
                        'params'=>!empty($item['params'])?$item['params']:[],
                    ]);
                }
            } ?>
        </ul>
    <?php endif ?>
</li>