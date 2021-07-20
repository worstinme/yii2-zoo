<?php

namespace worstinme\zoo\elements\system\category;

use Yii;
use yii\db\ActiveRecord;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    private $categories;

    public function rules()
    {
        $registrar = $this;
        return [
            [$this->attribute, 'each', 'rule' => ['integer']],
            ['parent_category_id', 'integer'],
            [$this->attribute, function ($attribute, $params, $validator) use ($registrar)  {
                if (empty($registrar->owner->parent_category_id) || !in_array($registrar->owner->parent_category_id, $registrar->getValue())) {
                    $registrar->owner->addError($attribute, 'PARENT_CATEGORY_REQUIRED');
                }
            }, 'skipOnEmpty' => true],
        ];
    }

    public function getScenarios()
    {
        return [
            'default' => [$this->attribute, 'parent_category_id']
        ];
    }

    public function getValue()
    {
        if ($this->categories === null && !$this->owner->isNewRecord) {
            $this->categories = \yii\helpers\ArrayHelper::getColumn($this->owner->categories, 'id');
        }
        return $this->categories;
    }

    public function setValue($value)
    {
        return $this->categories = $value;
    }

    public function afterSave($insert)
    {
        if ($this->isAttributeActive) {

            if (is_array($this->categories)) {

                $old_categories = [];

                foreach ($this->owner->categories as $category) {

                    if (!in_array($category->id, $this->categories)) {

                        Yii::$app->db->createCommand()->delete('{{%items_categories}}', ['item_id' => $this->owner->id, 'category_id' => $category->id])->execute();

                    } else {

                        $old_categories[] = $category->id;

                    }

                }

                foreach ($this->categories as $category_id) {

                    if (!in_array($category_id, $old_categories)) {

                        Yii::$app->db->createCommand()->insert('{{%items_categories}}', [
                            'item_id' => $this->owner->id,
                            'category_id' => $category_id,
                        ])->execute();

                    }

                }

            } else {
                Yii::$app->db->createCommand()->delete('{{%items_categories}}', ['item_id' => $this->owner->id])->execute();
            }
        }
    }

    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%items_categories}}', ['item_id' => $this->owner->id])->execute();
    }
}
