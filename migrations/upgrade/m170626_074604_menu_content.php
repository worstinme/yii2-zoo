<?php

use yii\db\Migration;

class m170626_074604_menu_content extends Migration
{
    public function up()
    {
        $this->addColumn('{{%zoo_menu}}','content',$this->text());
    }

    public function down()
    {
        echo "m170626_074604_menu_content cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
