<?php

use yii\db\Migration;

/**
 * Handles adding  to table ``.
 */
class m160524_145715_lang_categories__column extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%zoo_categories}}', 'lang', $this->string());
                
    }

    public function safeDown()
    {
        $this->dropColumn('{{%zoo_categories}}', 'lang');
    }
}
