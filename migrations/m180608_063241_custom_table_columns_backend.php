<?php

use yii\db\Migration;

/**
 * Class m180608_063241_custom_table_columns_backend
 */
class m180608_063241_custom_table_columns_backend extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('elements','show_in_table',$this->boolean()->defaultValue(0));
        $this->addColumn('elements','show_in_table_format',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180608_063241_custom_table_columns_backend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180608_063241_custom_table_columns_backend cannot be reverted.\n";

        return false;
    }
    */
}
