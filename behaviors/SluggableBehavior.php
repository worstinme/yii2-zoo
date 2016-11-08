<?php

namespace worstinme\zoo\behaviors;

use worstinme\zoo\helpers\Inflector;

class SluggableBehavior extends \yii\behaviors\SluggableBehavior
{
    public $slugAttribute = 'alias';
    public $attribute = 'name';
    public $ensureUnique = true;
    public $immutable = false;

    protected function isNewSlugNeeded()
    {
        if ($this->owner->isAttributeChanged($this->slugAttribute)) {
            return false;
        }

        if (empty($this->owner->{$this->slugAttribute})) {
            return true;
        }
        if ($this->immutable) {
            return false;
        }
        foreach ((array)$this->attribute as $attribute) {
            if ($this->owner->isAttributeChanged($attribute)) {
                return true;
            }
        }
        return false;
    }

    protected function generateSlug($slugParts)
    {
        return Inflector::slug(implode('-', $slugParts));
    }
}