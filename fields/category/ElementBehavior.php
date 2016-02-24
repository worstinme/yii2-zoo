<?php

namespace worstinme\zoo\fields\category;

use Yii;
use ArrayObject;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

class ElementBehavior extends \worstinme\zoo\fields\BaseElementBehavior
{
	public function rules($attributes)
	{
		return [
			['category','each','rule'=>['integer'],'message'=>'Выберите категорию'],
			['category','required'],
		];
	}

    public function attach($owner)
    {
        parent::attach($owner);
        if (!$this->owner->isNewRecord) {
            $this->owner->setElementValue('category', $this->owner->getCategories()->select('id')->column());
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
        Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id'=>$this->id])->execute();
    } 
}