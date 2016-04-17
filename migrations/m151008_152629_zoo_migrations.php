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
            'params' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%zoo_categories}}', [
            'id' => $this->primaryKey(),            
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'parent_id' => $this->integer()->defaultValue(0),
            'app_id' => $this->integer()->notNull(),            
            'sort' =>$this->integer()->defaultValue(0),
            'state' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'params' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%zoo_items}}', [
            'id' => $this->primaryKey(),   
            'app_id' => $this->integer()->notNull(),   
            'user_id' => $this->integer()->notNull(),
            'flag' =>$this->smallInteger()->defaultValue(0),
            'sort' =>$this->integer()->defaultValue(0),
            'state' => $this->smallInteger()->defaultValue(0),      
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'params' => $this->text(),
            'alias' => $this->string(),
            'name' => $this->string()->notNull(),
            
        ], $tableOptions);

        $this->createTable('{{%zoo_elements}}', [
            'id' => $this->primaryKey(),   
            'title' => $this->string()->notNull(),   
            'name' => $this->string()->notNull(),   
            'type' => $this->string()->notNull(), 
            'required'=>$this->boolean()->notNull()->defaultValue(0),
            'filter'=>$this->boolean()->notNull()->defaultValue(0),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),      
            'app_id' => $this->integer()->notNull(),
            'params' => $this->text(),
            
        ], $tableOptions);

        $this->createTable('{{%zoo_config}}', [
            'id' => $this->primaryKey(),   
            'category' => $this->string()->notNull(),   
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_name' => $this->string(),
            'value' => $this->text(),
        ], $tableOptions); 

        $this->createTable('{{%zoo_elements_categories}}', [  
            'element_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%zoo_items_categories}}', [  
            'item_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%zoo_items_elements}}', [  
            'id' => $this->primaryKey(),   
            'item_id' => $this->integer()->notNull(),
            'element' => $this->string()->notNull(),   
            'value_text' => $this->text(), 
            'value_int' => $this->integer(),
            'value_string' => $this->string(),  
            'value_float'=>$this->float(),
        ], $tableOptions);  
 
    }

    public function safeDown()
    {

        $this->dropTable('{{%zoo_applications}}');
        $this->dropTable('{{%zoo_categories}}');
        $this->dropTable('{{%zoo_items}}');
        $this->dropTable('{{%zoo_elements}}');
        $this->dropTable('{{%zoo_config}}');
        $this->dropTable('{{%zoo_elements_categories}}');
        $this->dropTable('{{%zoo_items_categories}}');
        $this->dropTable('{{%zoo_items_elements}}');
        
    }

}
