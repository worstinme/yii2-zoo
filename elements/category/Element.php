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
			['category','each','rule'=>['integer'],'message'=>'Выберите категорию'],
			//['category','required'],
		];
	}

    public function attach($owner)
    {
        parent::attach($owner);
        if (!$this->owner->isNewRecord) {
            $this->owner->setElementValue('category', \yii\helpers\ArrayHelper::getColumn($this->owner->categories, 'id'));
        }
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
        Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$this->owner->id])->execute();
        
        if (count($this->owner->category)) {
            
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