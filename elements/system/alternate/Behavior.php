<?php

namespace worstinme\zoo\elements\system\alternate;

use Yii;
use yii\helpers\ArrayHelper;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    public function rules()
    {
        return [
            [$this->attribute, 'each', 'rule' => ['integer']],
        ];
    }

    public function getValue()
    {
        if ($this->value === null && !$this->owner->isNewRecord) {
            $this->value = \yii\helpers\ArrayHelper::getColumn($this->owner->alternates, 'id');
        }
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function afterSave()
    {
        $values = is_array($this->value) ? $this->value : [];

        $old_alternates = [];

        foreach ($this->owner->alternates as $alternate) {

            if (!in_array($alternate->id, $values)) {

                Yii::$app->db->createCommand()->delete('{{%items_alternates}}', ['item_id' => $this->owner->id, 'alternate_id' => $alternate->id])->execute();
                Yii::$app->db->createCommand()->delete('{{%items_alternates}}', ['item_id' => $alternate->id, 'alternate_id' => $this->owner->id])->execute();

            } else {

                $old_alternates[] = $alternate->id;

            }

        }

        foreach ($values as $alternate_id) {

            if (!in_array($alternate_id, $old_alternates)) {

                Yii::$app->db->createCommand()->batchInsert('{{%items_alternates}}', ['item_id', 'alternate_id'], [
                    [$this->owner->id, $alternate_id],
                    [$alternate_id, $this->owner->id],
                ])->execute();

            }

        }
    }

    public function afterDelete()
    {
        foreach ($this->owner->alternates as $alternate) {

            Yii::$app->db->createCommand()->delete('{{%items_alternates}}', ['item_id' => $this->owner->id, 'alternate_id' => $alternate->id])->execute();
            Yii::$app->db->createCommand()->delete('{{%items_alternates}}', ['item_id' => $alternate->id, 'alternate_id' => $this->owner->id])->execute();

        }
    }

}