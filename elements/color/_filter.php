<?php

use worstinme\zoo\elements\color\HtmlHelper;
use yii\helpers\ArrayHelper;


$varQuery = !empty($extended) && $extended ? clone $searchModel->query() : clone $searchModel->query;

$attribute_sq = $element->name.'.value_string';

$j = false; foreach ($varQuery->join as $join)  if (isset($join[1][$element->name])) $j = true;  

if (!$j) $varQuery->leftJoin([$element->name=>'{{%zoo_items_elements}}'], $element->name.".item_id = a.id AND ".$element->name.".element = '".$element->name."'"); 
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

<label class="f-label">Цвет</label>

<?= HtmlHelper::activeCheckboxList($searchModel, 'color', $variants,['class'=>'color-filter']) ?>

<?php 

}