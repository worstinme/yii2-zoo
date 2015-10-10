<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_152629_zoo_migrations extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
 
        $this->createTable('{{%zoo_applications}}', [
            'id' => $this->primaryKey(),            
            'name' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'sort' =>$this->integer()->notNull()->defaultValue(0),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'params' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%zoo_categories}}', [
            'id' => $this->primaryKey(),            
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'type'=>$this->integer()->defaultValue(0),
            'parent_id' => $this->integer()->defaultValue(0),
            'app_id' => $this->integer()->notNull(),            
            'sort' =>$this->integer()->notNull()->defaultValue(0),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'params' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%zoo_items}}', [
            'id' => $this->primaryKey(),   
            'app_id' => $this->integer()->notNull(),   
            'user_id' => $this->integer()->notNull(),
            'flag' =>$this->integer()->notNull()->defaultValue(0),
            'sort' =>$this->integer()->notNull()->defaultValue(0),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),      
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'params' => $this->text(),
            
        ], $tableOptions);

        $this->createTable('{{%zoo_fields}}', [
            'id' => $this->primaryKey(),   
            'title' => $this->string()->notNull(),   
            'name' => $this->string()->notNull(),   
            'type' => $this->string()->notNull(), 
            'required'=>$this->boolean()->notNull()->defaultValue(0),
            'filter'=>$this->boolean()->notNull()->defaultValue(0),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),      
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'params' => $this->text(),
            
        ], $tableOptions);

        $this->createTable('{{%zoo_config}}', [
            'id' => $this->primaryKey(),   
            'category' => $this->string()->notNull(),   
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_name' => $this->string(),
            'value' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%zoo_fields_categories}}', [  
            'field_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'app_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%zoo_items_categories}}', [  
            'field_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%zoo_items_fields}}', [  
            'item_id' => $this->integer()->notNull(),
            'field_id' => $this->integer()->notNull(),
            'value' => $this->text(), 
            'variant' => $this->integer(),
        ], $tableOptions);  
 
    }

    public function safeDown()
    {

        $this->dropTable('{{%zoo_applications}}');
        $this->dropTable('{{%zoo_categories}}');
        $this->dropTable('{{%zoo_items}}');
        $this->dropTable('{{%zoo_fields}}');
        $this->dropTable('{{%zoo_config}}');
        $this->dropTable('{{%zoo_fields_categories}}');
        $this->dropTable('{{%zoo_items_categories}}');
        $this->dropTable('{{%zoo_items_fields}}');
        
    }

}
