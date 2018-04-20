<?php

use yii\db\Migration;

/**
 * Class m180420_074349_item_additional_state
 */
class m180420_074349_item_additional_state extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%items}}','bolt',$this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%items}}','bolt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180420_074349_item_additional_state cannot be reverted.\n";

        return false;
    }
    */
}
