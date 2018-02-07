<?php
namespace worstinme\zoo\frontend\models;


class Items extends \worstinme\zoo\models\Items
{
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])
            ->andOnCondition([Categories::tableName().'.state'=>1])
            ->viaTable('{{%items_categories}}', ['item_id' => 'id']);
    }

    public function getParentCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_category_id'])
            ->andOnCondition([Categories::tableName().'.state'=>1]);
    }

    public function getAlternates() {
        return $this->hasMany(Items::className(),['id'=>'alternate_id'])
            ->andOnCondition([Items::tableName().'.state'=>1])
            ->viaTable('{{%items_alternates}}',['item_id'=>'id']);
    }

}