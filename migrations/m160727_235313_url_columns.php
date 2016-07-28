<?php

use yii\db\Migration;

class m160727_235313_url_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%zoo_categories}}', 'url', $this->string());
        $this->addColumn('{{%zoo_items}}', 'url', $this->string());
        $this->addColumn('{{%zoo_items_categories}}', 'main', $this->boolean());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%zoo_categories}}', 'url');
        $this->dropColumn('{{%zoo_items}}', 'url', $this->string());
        $this->dropColumn('{{%zoo_items_categories}}', 'main', $this->boolean());
    }
}
