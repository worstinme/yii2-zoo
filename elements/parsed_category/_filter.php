<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$variants = (new \yii\db\Query())
    ->select(['value_string'])
    ->from('{{%zoo_items_elements}}')
    ->where(['element'=>$element->name])
    ->groupBy('value_string')
    ->orderBy('count(item_id) DESC')
    ->column();  

// $variants = $searchModel->search(Yii::$app->request->queryParams)->select('material.value_string')->groupBy('material.value_string')->andWhere('material.value_string IS NOT NULL')->orderBY('count(material.value_string) DESC')->column(); 

$variants = ArrayHelper::index($variants, function ($element) {return $element;}); 

/* 

$values = $searchModel->{$element->name};

if(is_array($values) && count($values))
foreach ($values as $value) {
    if ($value !== null && !empty($value)) {
        $variants[$value] = $value;
    }
}

*/

?>

<label class="f-label"><?=$element->title?></label>

<?= Html::activeCheckboxList($searchModel, $element->name, $variants, ['class'=>$element->name.'-filter checkbox-filter']) ?>

<?php if (count($variants) > 5): ?>
	<?= Html::a('Показать все', '#', ['class' => 'dfn','data-uk-toggle'=>"{cls: 'active', target:'#".Html::getInputId($searchModel, $element->name)."'}"]); ?>
<?php endif ?>