<?php

namespace worstinme\zoo\backend\models;

/**
 * This is the ActiveQuery class for [[Items]].
 *
 * @see Items
 */
class ItemsQuery extends \yii\db\ActiveQuery
{
    public function init()
    {
       // $modelClass = $this->modelClass;
       // $tableName = $modelClass::tableName();
        $this->joinWith([
            //'itemsElements',
            //'elements',
            //'categories'
        ]);

        parent::init();
    }
   /* public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Items[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Items|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}