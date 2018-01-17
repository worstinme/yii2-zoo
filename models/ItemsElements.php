<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%items_elements}}".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $element_id
 * @property string $value_text
 * @property integer $value_int
 * @property string $value_string
 * @property double $value_float
 */
class ItemsElements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%items_elements}}';
    }

}
