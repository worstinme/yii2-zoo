<?php

use yii\db\Migration;

class m160728_003058_change_urls extends Migration
{
    public function safeUp()
    {

        $categories = (new \yii\db\Query())->select(['app_id','id','parent_id','alias'])->from('{{%zoo_categories}}')->indexBy('id')->all();

        foreach ($categories as $category) {
            $url = $this->concatParentAlias('',$category['parent_id'],$categories).$category['alias'];
            $category['url'] = $url;
            $this->update('{{%zoo_categories}}',['url'=>$url],'id = :id',[':id'=>$category['id']]);
        }

        $applications = (new \yii\db\Query())->select(['id','params'])->from('{{%zoo_applications}}')->indexBy('id')->all();

        $items = (new \yii\db\Query())->select(['app_id','id','alias'])->from('{{%zoo_categories}}')->all();

        $ic = (new \yii\db\Query())->select(['item_id','category_id'])->from('{{%zoo_items_categories}}')->all();

        $items_c = [];

        foreach ($ic as $key => $value) {
            if (!empty($categories[$value['category_id']])) {
                $items_c[$value['id']][] = $categories[$value['category_id']];
            }
        }

        foreach ($items_c as $item => $categories) {
            foreach ($categories as $category) {
                if (!empty($categories[$category['parent_id']])) {
                    
                }
            }
        }

    }

    public function safeDown()
    {
        
    }

    protected function concatParentAlias($url,$parent_id,$categories) {
        if (!empty($parent_id) && !empty($categories[$parent_id])) {
            $parent = $categories[$parent_id];
            return $this->concatParentAlias($url,$parent['parent_id'],$categories).$parent['alias'].'/';
        }
        return '';
    }
}
