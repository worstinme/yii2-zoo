<?php

use yii\db\Migration;

class m160627_215323_application_app_alias extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%zoo_applications}}', 'app_alias', $this->string());
                
    }

    public function safeDown()
    {
        $this->dropColumn('{{%zoo_applications}}', 'app_alias');
    }

}
