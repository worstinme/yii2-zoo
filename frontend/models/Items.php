<?php
namespace worstinme\zoo\frontend\models;


class Items extends \worstinme\zoo\models\Items
{
    public function getCategories()
    {
        return parent::getCategories()->where(['state'=>1]);
    }

    public function getParentCategory()
    {
        return parent::getParentCategory()->where(['state'=>1]);
    }

    public function getAlternates() {
        return parent::getAlternates()->where(['state'=>1]);
    }
}