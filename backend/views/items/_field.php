<div class="field-<?=$attribute?>"><?php 

if ($view == '_form' && in_array($attribute,$model->renderedElements)) {

	echo $this->render('@worstinme/zoo/fields/'.$model->elements[$attribute]['type'].'/_form.php',[
	                        'model'=>$model,
	                        'attribute'=>$attribute,
	                    ]);



} 
elseif ($view == '_view') {

	$type = Yii::$app->controller->app->elements[$attribute]['type'];
	
	echo $this->render('@worstinme/zoo/fields/'.$type.'/_view.php',[
	                        'model'=>$model,
	                        'attribute'=>$attribute,
	                    ]);

} ?></div>