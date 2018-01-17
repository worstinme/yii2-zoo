<?php

use yii\db\Migration;

class m171102_075353_select_multiple extends Migration
{
    public function safeUp()
    {
        $this->db->createCommand("UPDATE {{zoo_items_elements}} ie LEFT JOIN {{zoo_elements}} as e ON e.name = ie.element SET value_int = value_text WHERE e.type = 'select'")->execute();
        $this->db->createCommand("UPDATE {{zoo_items_elements}} ie LEFT JOIN {{zoo_elements}} as e ON e.name = ie.element SET value_text = NULL WHERE e.type = 'select'")->execute();

        $this->db->createCommand("UPDATE {{zoo_items_elements}} ie LEFT JOIN {{zoo_elements}} as e ON e.name = ie.element SET value_int = value_string WHERE e.type = 'select_multiple'")->execute();
        $this->db->createCommand("UPDATE {{zoo_items_elements}} ie LEFT JOIN {{zoo_elements}} as e ON e.name = ie.element SET value_string = NULL WHERE e.type = 'select_multiple'")->execute();
    }

    public function safeDown()
    {
        echo "m171102_075353_select_multiple cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_075353_select_multiple cannot be reverted.\n";

        return false;
    }
    */
}
