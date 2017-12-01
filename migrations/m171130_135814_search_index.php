<?php

use yii\db\Migration;

class m171130_135814_search_index extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        }

        $this->createTable('{{%zoo_search}}', [
            'item_id' => $this->integer()->notNull(),
            'search'=>$this->text(),
        ], $tableOptions);

        $this->execute("ALTER TABLE {{%zoo_search}} ADD FULLTEXT INDEX `idx-search` (search ASC)");
        $this->createIndex('idx-item_id','{{%zoo_search}}','item_id',true);

    }

    public function safeDown()
    {
        $this->dropTable('{{%zoo_search}}');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171130_135814_search_index cannot be reverted.\n";

        return false;
    }
    */
}
