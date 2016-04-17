<?php echo "<?php"; ?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="items-filter">

	<?php echo "<?php"; ?> $form = ActiveForm::begin([
		'action'=> yii\helpers\Url::current(),
        'method' => 'get',
        'options'=>['class'=>'','data-pjax' => true],
    ]); ?>

    <h4>Подбор по характеристикам</h4> 

	<?php echo "<?php"; ?> foreach ($app->elements as $element): ?>
		<?php echo "<?php"; ?> if ($element->filter == 1): ?>
			<div class="filter"><?php echo "<?="; ?>$this->render('@worstinme/zoo/elements/'.$element->type.'/_filter.php',['element'=>$element,'searchModel'=>$searchModel,'form'=>$form]); ?></div>
		<?php echo "<?php"; ?> endif ?>
	<?php echo "<?php"; ?> endforeach ?>

    <div class="form-group uk-margin-top uk-text-center">
        <?php echo "<?="; ?> Html::submitButton('Поиск', ['class' => 'submit-button']) ?>
    </div>

	<?php echo "<?php"; ?> ActiveForm::end(); ?>
 
</div>