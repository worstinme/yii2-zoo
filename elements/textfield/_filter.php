<?php

use yii\helpers\Html;

$query = $searchModel->query;

$variants = (new \yii\db\Query())
    ->select(['value_string'])
    ->from('{{%zoo_items_elements}}')
    ->where(['element'=>$element->name])
    ->indexBy('value_string')
    ->groupBy('value_string')
    ->orderBy('count(item_id) DESC')
    ->column();

?>

<label class="f-label"><?=$element->title?></label>

<?= Html::activeCheckboxList($searchModel, $element->name, $variants, ['class'=>$element->name.'-filter checkbox-filter']) ?>

<?php if (count($variants) > 5): ?>
	<?= Html::a('Показать все', '#', ['class' => 'dfn','data-uk-toggle'=>"{cls: 'active', target:'#".Html::getInputId($searchModel, $element->name)."'}"]); ?>
<?php endif ?>