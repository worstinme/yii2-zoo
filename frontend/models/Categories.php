<?php
/**
 * Created by PhpStorm.
 * User: worst
 * Date: 16.01.2018
 * Time: 11:18
 */

namespace worstinme\zoo\frontend\models;


class Categories extends \worstinme\zoo\models\Categories
{
    public function getRelated()
    {
        return parent::getRelated()->where(['state'=>1]);
    }

    public function getItems()
    {
        return parent::getItems()->where(['state'=>1]);
    }

    public function getParentCategory()
    {
        return parent::getParentCategory()->where(['state'=>1]);
    }

    public function getAlternates() {
        return parent::getAlternates()->where(['state'=>1]);
    }

    public function getBreadcrumbs($selfUrl = false)
    {
        $crumbs = $selfUrl ? [['label' => $this->name, 'url' => $this->url]] : [$this->name];
        $parent = $this->parentCategory;
        while ($parent !== null) {
            $crumbs[] = ['label' => $parent->name, 'url' => $parent->url];
            $parent = $parent->parent;
        }
        $crumbs[] = ['label' => $this->app->title, 'url' => $this->app->url];
        return array_reverse($crumbs);
    }

}
