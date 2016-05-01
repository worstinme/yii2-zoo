<?php

use yii\db\Migration;

class m160428_084227_widgets_menu extends Migration
{
    public function safeUp()
    {

        $this->createTable('{{%zoo_widgets}}', [
            'id' => $this->primaryKey(),   
            'type' => $this->string(),         
            'name' => $this->string()->notNull(),
            'position' => $this->string(),
            'bound' => $this->string(),
            'params'=> $this->text(),
        ], $tableOptions);

        $this->createTable('{{%zoo_menu}}', [
            'id' => $this->primaryKey(),   
            'type' => $this->string(),         
            'name' => $this->string()->notNull(),
            'menu' => $this->string(),
            'params'=> $this->text(),
            'parent_id' => $this->integer()->defaultValue(0),
            'type' => $this->integer()->notNull(),
            'application_id' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer()->defaultValue(0),
            'item_id' => $this->integer()->defaultValue(0),
            'sort' => $this->integer(),
            'url'=> $this->text(),
        ], $tableOptions);
                
    }

    public function safeDown()
    {
        $this->dropTable('{{%zoo_widgets}}');
        $this->dropTable('{{%zoo_menu}}');
    }

}
