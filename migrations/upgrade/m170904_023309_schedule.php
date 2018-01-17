<?php

use yii\db\Migration;
use yii\db\Query;

class m170904_023309_schedule extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%zoo_schedule}}', [
            'id' => $this->primaryKey(),
            'mo' => $this->boolean(),
            'tu' => $this->boolean(),
            'we' => $this->boolean(),
            'th' => $this->boolean(),
            'fr' => $this->boolean(),
            'sa' => $this->boolean(),
            'su' => $this->boolean(),
            'start_at' => $this->integer()->unsigned()->notNull(),
            'finish_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m170904_023309_schedule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170904_023309_schedule cannot be reverted.\n";

        return false;
    }
    */
}
