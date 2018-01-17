<?php

use yii\db\Migration;

class m171102_071909_indexes extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx-element', '{{zoo_items_elements}}','element');
        $this->createIndex('idx-item_id', '{{zoo_items_elements}}','item_id');
        $this->createIndex('idx-value_int', '{{zoo_items_elements}}','value_int');
        $this->createIndex('idx-value_string', '{{zoo_items_elements}}','value_string');
        $this->createIndex('idx-value_float', '{{zoo_items_elements}}','value_float');

        $this->createIndex('idx-app_id', '{{zoo_elements}}','app_id');
        $this->createIndex('idx-type', '{{zoo_elements}}','type');
        $this->createIndex('idx-name', '{{zoo_elements}}','name');

        $this->createIndex('idx-app_id', '{{zoo_items}}','app_id');
        $this->createIndex('idx-flag', '{{zoo_items}}','flag');
        $this->createIndex('idx-state', '{{zoo_items}}','state');
        $this->createIndex('idx-lang', '{{zoo_items}}','lang');
        $this->createIndex('idx-alias', '{{zoo_items}}','alias');

        $this->createIndex('idx-item_id-category_id', '{{zoo_items_categories}}',['item_id','category_id']);
        $this->createIndex('idx-category_id-item_id', '{{zoo_items_categories}}',['category_id','item_id']);

        $this->createIndex('idx-element_id-category_id', '{{zoo_elements_categories}}',['element_id','category_id']);

        $this->createIndex('idx-app_id', '{{zoo_categories}}','app_id');
        $this->createIndex('idx-state', '{{zoo_categories}}','state');
        $this->createIndex('idx-lang', '{{zoo_categories}}','lang');
        $this->createIndex('idx-flag', '{{zoo_categories}}','flag');
        $this->createIndex('idx-sort', '{{zoo_categories}}','sort');
        $this->createIndex('idx-parent_id', '{{zoo_categories}}','parent_id');
    }

    public function safeDown()
    {
        echo "m171102_071909_indexes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_071909_indexes cannot be reverted.\n";

        return false;
    }
    */
}
