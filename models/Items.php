<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%zoo_items}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $flag
 * @property integer $sort
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $params
 */
class Items extends \yii\db\ActiveRecord
{
    private $fields;
    private $categories;
    private $expectedFields;

    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    {
        return [
            [['user_id', 'created_at', 'updated_at','categories'], 'required'],
            [['user_id', 'flag', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            ['categories','each','rule'=>['integer'],'message'=>'Выберите категорию'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'user_id' => Yii::t('admin', 'User ID'),
            'flag' => Yii::t('admin', 'Flag'),
            'sort' => Yii::t('admin', 'Sort'),
            'state' => Yii::t('admin', 'State'),
            'created_at' => Yii::t('admin', 'Created At'),
            'updated_at' => Yii::t('admin', 'Updated At'),
            'params' => Yii::t('admin', 'Params'),
        ];
    }

    /**
     * @inheritdoc
     * @return ItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemsQuery(get_called_class());
    }

    public function getFields() {
        if(!count($this->fields)) {
            $categories = $this->getCategories();
            array_push($categories, 0);
            $this->fields = Field::find()
                    ->leftJoin('{{%zoo_fields_categories}}','{{%zoo_fields_categories}}.field_id = id')
                    ->where(['{{%zoo_fields_categories}}.category_id'=>$categories])
                    ->indexBy("id")
                    ->all();
        }        
        return $this->fields;
    }

    public function getExpectedFields() {
        if(!count($this->expectedFields)) {
            $this->expectedFields = (new \yii\db\Query())
                ->select(['id'])
                ->from('{{%zoo_fields}}')
                ->where(['app_id' => Yii::$app->controller->application->id])
                ->column();
        }
        return $this->expectedFields;
    }

    public function getElements() {
        return $this->hasMany(ItemFields::className(), ['item_id' => 'id']);
    }

     public function getCategories() {
        if (!count($this->categories) && !$this->isNewRecord) {
            $this->categories = (new \yii\db\Query())
                    ->select('category_id')
                    ->from('{{%zoo_items_categories}}')
                    ->where(['item_id'=>$this->id])
                    ->column();
        }
        if (!count($this->categories)) {
            return [];
        }
        return $this->categories;
    }

    public function setCategories($array) {
        if (count($array)) {
            foreach ($array as $key => $value) {
                if ($value == '' || $value == 0) {
                    unset($array[$key]);
                }
            }
        }
        else {
            $array = [];
        }
        $this->categories = $array;
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $db = Yii::$app->db;
        if (count($this->categories)) {
            
            $db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$this->id])->execute();

            foreach ($this->categories as $category) {
                $db->createCommand()->insert('{{%zoo_items_categories}}', [
                        'item_id' => $this->id,
                        'category_id' => (int)$category,
                    ])->execute();
            }    

        }

        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()->delete('items_categories', ['item_id'=>$this->id])->execute();
    }
}
