<?php

namespace worstinme\zoo\models;

/**
 * This is the ActiveQuery class for [[ItemFields]].
 *
 * @see ItemFields
 */
class ItemFieldsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ItemFields[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ItemFields|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}