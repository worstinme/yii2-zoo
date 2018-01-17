<?php

use yii\db\Migration;

class m170501_065031_categories_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%zoo_categories}}','flag',$this->smallInteger(1)->defaultValue(0));
        $this->addColumn('{{%zoo_categories}}','icon',$this->string());
    }

    public function down()
    {
        echo "m170501_065031_categories_fields cannot be reverted.\n";

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
