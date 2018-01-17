<?php

namespace worstinme\zoo\elements\system\category;


class Element extends \worstinme\zoo\elements\system\Element
{
    public function getIsAvailable()
    {
        return $this->app->getCategories()->count() ? true : false;
    }
}