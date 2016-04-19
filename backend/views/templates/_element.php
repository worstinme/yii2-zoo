<li class="uk-nestable-item" data-element="<?=$element->name?>">
    <div class="uk-nestable-panel">
        <i class="nestable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
        <?=$element->label?>
        <?php if (!empty($element->paramsView)): ?>
        <?=$this->render($element->paramsView,[
	    	'element'=>$element,
	    	'params'=>$params,
	    ])?>	
        <?php endif ?>
       
    </div>
</li>