<?php

use yii\db\Migration;

class m160722_165258_category_user_column extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%zoo_categories}}', 'user_id', $this->integer());
                
    }

    public function safeDown()
    {
        $this->dropColumn('{{%zoo_categories}}', 'user_id');
    }
}
