<?php

use yii\db\Migration;

/**
 * Class m180213_144317_zoo_init
 */
class m180213_144317_zoo_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey()->unsigned(),
            'app_id' => $this->string()->notNull(),
            'lang' => $this->string(),
            'alias' => $this->string(),
            'state' => $this->boolean()->defaultValue(0),
            'flag' => $this->boolean()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'parent_category_id'=>$this->integer()->unsigned(),
            'created_at' => $this->integer()->notNull()->unsigned(),
            'updated_at' => $this->integer()->notNull()->unsigned(),
            'meta_keywords' => $this->string(),
            'meta_title' => $this->string(),
            'meta_description' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-items','{{%items}}',['app_id','lang','state','alias','flag']);

        $this->createTable('{{%items_alternates}}', [
            'item_id' => $this->integer()->unsigned()->notNull(),
            'alternate_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-items_alternates', '{{%items_alternates}}', ['item_id', 'alternate_id']);

        $this->createTable('{{%items_categories}}', [
            'item_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-items_categories', '{{%items_categories}}', ['item_id', 'category_id']);

        $this->createTable('{{%items_elements}}', [
            'id' => $this->primaryKey()->unsigned(),
            'item_id' => $this->integer()->unsigned()->notNull(),
            'element' => $this->string()->notNull(),
            'value_text' => $this->text(),
            'value_string' => $this->string(),
            'value_int' => $this->integer(),
            'value_float' => $this->float(),
        ], $tableOptions);

        $this->createIndex('idx-items_elements-item_id','{{%items_elements}}','item_id');
        $this->createIndex('idx-items_elements-element','{{%items_elements}}','element');
        $this->createIndex('idx-items_elements-value_int','{{%items_elements}}','value_int');
        $this->createIndex('idx-items_elements-value_float','{{%items_elements}}','value_float');
        $this->createIndex('idx-items_elements-value_string','{{%items_elements}}','value_string');

        $this->createTable('{{%applications_content}}', [
            'id' => $this->primaryKey()->unsigned(),
            'app_id' => $this->string()->notNull(),
            'lang' => $this->string(),
            'name' => $this->string()->notNull(),
            'content'=>$this->text(),
            'intro'=>$this->text(),
            'created_at' => $this->integer()->notNull()->unsigned(),
            'updated_at' => $this->integer()->notNull()->unsigned(),
            'meta_keywords' => $this->string(),
            'meta_title' => $this->string(),
            'meta_description' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-applications_content','{{%applications_content}}',['app_id','lang']);

        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey()->unsigned(),
            'app_id' => $this->string()->notNull(),
            'lang' => $this->string(),
            'alias' => $this->string(),
            'state' => $this->boolean(),
            'flag' => $this->boolean()->defaultValue(0),
            'state' => $this->boolean()->defaultValue(0),
            'sort' => $this->integer()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'parent_id'=>$this->integer()->unsigned(),
            'icon' => $this->string(),
            'preview' => $this->string(),
            'image' => $this->string(),
            'quote' => $this->string(),
            'subtitle' => $this->string(),
            'content' => $this->text(),
            'intro' => $this->text(),
            'created_at' => $this->integer()->notNull()->unsigned(),
            'updated_at' => $this->integer()->notNull()->unsigned(),
            'meta_keywords' => $this->string(),
            'meta_title' => $this->string(),
            'meta_description' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-categories','{{%categories}}',['app_id','lang','state','alias','flag']);
        $this->createIndex('idx-categories-parent_id','{{%categories}}','parent_id');

        $this->createTable('{{%categories_alternates}}', [
            'category_id' => $this->integer()->unsigned()->notNull(),
            'alternate_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-categories_alternates', '{{%categories_alternates}}', ['category_id', 'alternate_id']);

        $this->createTable('{{%elements}}', [
            'id' => $this->primaryKey()->unsigned(),
            'app_id' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'label' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'hint' => $this->string(),
            'params' => $this->text(),
            'required'=> $this->boolean()->defaultValue(0),
            'all_categories'=>$this->boolean()->defaultValue(1),
            'own_column'=>$this->boolean()->defaultValue(0),
            'sort'=>$this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-elements-app_id','{{%elements}}','app_id');
        $this->createIndex('idx-elements-name','{{%elements}}','name');
        $this->createIndex('idx-elements','{{%elements}}',['app_id','sort']);

        $this->createTable('{{%elements_categories}}', [
            'element_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-elements_categories', '{{%elements_categories}}', ['element_id', 'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180213_144317_zoo_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_144317_zoo_init cannot be reverted.\n";

        return false;
    }
    */
}
