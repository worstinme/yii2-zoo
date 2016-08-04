<?php

namespace worstinme\zoo\widgets;

use Yii;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\ItemsElements;

class LastItems extends \worstinme\widgets\widgets\Widget
{
   	public $sort;
    public $desc;
    public $flag;
    public $app_id;
    public $categories;
    public $template = 'related';
    public $list_class;

    public function init()
    {
        parent::init();
    }

    public function run()
    {

    	$model = new Items;

    	$query = Items::find()->where(['app_id'=>$this->app_id,'flag'=>$this->flag?1:0]);

    	if (count($this->categories)) {
    		$query->leftJoin(['c'=>'{{%zoo_items_categories}}'],'c.item_id = {{%zoo_items}}.id');
    		$query->andFilterWhere(['c.category_id'=>$this->categories]);
    	}

    	if (!empty($this->sort) && !in_array($this->sort, $model->attributes())) {
    		$query->leftJoin(['e'=>'{{%zoo_items_elements}}'], "e.item_id = {{%zoo_items}}.id AND e.element = '".$this->sort."'");
    		if ($this->desc) {
    			$query->orderBy('e.value_int DESC, e.value_string DESC');
    		}
    		else {
    			$query->orderBy('e.value_int ASC, e.value_string ASC');
    		}
    	}
    	else {
    		if ($this->desc) {
    			$query->orderBy('{{%zoo_items}}.'.$this->sort.' DESC');
    		} else {
    			$query->orderBy('{{%zoo_items}}.'.$this->sort.' ASC');
    		}
    	}

    	$items = $query->all();
        
        return $this->render('last-items',[
        	'items'=>$items,
        ]); 
    }  

}