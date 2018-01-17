<?php

use yii\db\Migration;

/**
 * Handles adding  to table ``.
 */
class m160522_074105_lang_items_column extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%zoo_items}}', 'lang', $this->string());
                
    }

    public function safeDown()
    {
        $this->dropColumn('{{%zoo_items}}', 'lang');
    }
}
