<?php

namespace worstinme\zoo\elements\category;

use Yii;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

	public function rules($attributes)
	{
		return [
			['category','safe'],
			//['category','required','message'=>'Выберите категорию'],
		];
	}

    public function attach($owner)
    {
        parent::attach($owner);
        if (!$this->owner->isNewRecord) {
            $this->owner->values['category'] = \yii\helpers\ArrayHelper::getColumn($this->owner->categories, 'id');
        }
    }

    public function getValue($attribute = 'category') {
        return !empty($this->owner->values['category'])?$this->owner->values['category']:[];
    }

    public function setValue($attribute,$value) {
        return $this->owner->values[$attribute] = $value;
    }

	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
		];
	}

    public function afterSave()
    {
        
        if (count($this->owner->category)) {

            Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$this->owner->id])->execute();
            
            foreach ($this->owner->category as $category) {
                Yii::$app->db->createCommand()->insert('{{%zoo_items_categories}}', [
                        'item_id' => $this->owner->id,
                        'category_id' => (int)$category,
                    ])->execute();
            }    

        }
    } 

    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$this->owner->id])->execute();
    } 
}