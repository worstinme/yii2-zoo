<?php

use yii\db\Migration;

class m171030_150738_elements_fix extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{zoo_elements}}','admin_hint', $this->string());
        $this->addColumn('{{zoo_elements}}','sorter', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','filter', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','admin_filter', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','refresh', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','required', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','text_index', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','all_categories', $this->boolean()->defaultValue(0));
        $this->addColumn('{{zoo_elements}}','own_column', $this->smallInteger()->defaultValue(0));

        $this->db->getSchema()->refresh();

        $elements = (new \yii\db\Query())->select(['id','params'])->from('{{zoo_elements}}')->all();

        foreach ($elements as $element) {

            $params = $element['params'] ? \yii\helpers\Json::decode($element['params']) : [];

            $this->db->createCommand()->update('{{zoo_elements}}',[
                'admin_hint'=>$params['adminHint']??0,
                'sorter'=>$params['sorter']??0,
                'filter'=>$params['filter']??0,
                'admin_filter'=>$params['adminFilter']??0,
                'refresh'=>$params['refresh']??0,
                'required'=>$params['required']??0,
                'text_index'=>$params['text_index']??0,
                'all_categories'=>$params['allcategories']??0,
            ],['id'=>$element['id']])->execute();

        }

    }

    public function safeDown()
    {
        echo "m171030_150738_elements_fix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171030_150738_elements_fix cannot be reverted.\n";

        return false;
    }
    */
}
