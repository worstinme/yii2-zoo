<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$varQuery = clone $searchModel->query;

$attribute_sq = $element->name.'.value_string';

$j = false; foreach ($varQuery->join as $join)  if (isset($join[1][$element->name])) $j = true;  

if (!$j) $varQuery->leftJoin([$element->name=>'{{%items_elements}}'], $element->name.".item_id = a.id AND ".$element->name.".element = '".$element->name."'"); 

$variants = $varQuery->select($attribute_sq)
                ->groupBy($attribute_sq)
                ->andWhere($attribute_sq.' IS NOT NULL')
                ->orderBY('count('.$attribute_sq.') DESC')
                ->limit(30)
                ->column(); 

$variants = ArrayHelper::index($variants, function ($element) {return $element;}); 

$values = $searchModel->{$element->name};

if(is_array($values) && count($values))
foreach ($values as $value) {
    if ($value !== null && !empty($value)) {
        $variants[$value] = $value;
    }
}

if (count($variants)) {

?>

<label class="f-label"><?=$element->title?></label>

<?= Html::activeCheckboxList($searchModel, $element->name, $variants, ['class'=>$element->name.'-filter checkbox-filter']) ?>

<?php if (count($variants) > 5): ?>
	<?= Html::a('Показать все', '#', ['class' => 'dfn','data-uk-toggle'=>"{cls: 'active', target:'#".Html::getInputId($searchModel, $element->name)."'}"]); ?>
<?php endif ?>

<?php }