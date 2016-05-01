<?php

use yii\helpers\Html;

$id = $element->name.uniqid();

?><li class="uk-nestable-item" data-element="<?=$element->name?>">
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
</li>