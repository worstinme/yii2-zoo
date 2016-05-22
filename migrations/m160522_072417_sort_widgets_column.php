<?php

use yii\db\Migration;

/**
 * Handles adding  to table ``.
 */
class m160522_072417_sort_widgets_column extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%zoo_widgets}}', 'sort', $this->integer());
                
    }

    public function safeDown()
    {
        $this->dropColumn('{{%zoo_widgets}}', 'sort');
    }
}
