<?php

use worstinme\zoo\elements\color\HtmlHelper;

$colors = (new \yii\db\Query())
    ->select(['value_string'])
    ->from('{{%zoo_items_elements}}')
    ->where(['element'=>$element->name])
    ->groupBy('value_string')
    ->orderBy('count(item_id) DESC')
    ->limit(20)
    ->column(); 
?>

<label class="f-label">Цвет</label>

<?= HtmlHelper::activeCheckboxList($searchModel, 'color', $colors,['class'=>'color-filter']) ?>