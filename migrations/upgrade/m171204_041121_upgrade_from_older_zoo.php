<?php

use yii\db\Migration;

/**
 * Class m171204_041121_upgrade_from_older_zoo
 */
class m171204_041121_upgrade_from_older_zoo extends Migration
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

      /*   $this->alterColumn('{{%zoo_items}}','app_id',$this->string()->notNull());
         $this->alterColumn('{{%zoo_categories}}','app_id',$this->string()->notNull());
         $this->alterColumn('{{%zoo_elements}}','app_id',$this->string()->notNull());

         $this->execute('UPDATE {{%zoo_items}} i LEFT JOIN {{%zoo_applications}} a ON a.id = i.app_id SET app_id = a.name');
         $this->execute('UPDATE {{%zoo_categories}} i LEFT JOIN {{%zoo_applications}} a ON a.id = i.app_id SET i.app_id = a.name');
         $this->execute('UPDATE {{%zoo_elements}} i LEFT JOIN {{%zoo_applications}} a ON a.id = i.app_id SET i.app_id = a.name');

         $this->addColumn('zoo_elements','sort',$this->integer()->unsigned());

         $this->db->createCommand("DELETE from {{%zoo_elements}} WHERE type IN ('category','name','alias','lang','alternate')")->execute();

         $this->addColumn('{{%zoo_items}}','meta_keywords', $this->string());
         $this->addColumn('{{%zoo_items}}','meta_title', $this->string());
         $this->addColumn('{{%zoo_items}}','meta_description', $this->text());

         $items = (new \yii\db\Query())->select(['id','params'])->from('{{%zoo_items}}')->all();

         foreach ($items as $item) {
             $params = $item['params'] ? \yii\helpers\Json::decode($item['params']) : [];
             if (isset($params['metaTitle']) && !empty($params['metaTitle'])) {
                 $this->db->createCommand()->update('{{%zoo_items}}',[
                     'meta_title'=>$params['metaTitle']
                 ])->execute();
             }
             if (isset($params['metaKeywords']) && !empty($params['metaKeywords'])) {
                 $this->db->createCommand()->update('{{%zoo_items}}',[
                     'meta_keywords'=>$params['metaKeywords']
                 ])->execute();
             }
             if (isset($params['metaDescription']) && !empty($params['metaDescription'])) {
                 $this->db->createCommand()->update('{{%zoo_items}}',[
                     'meta_description'=>$params['metaDescription']
                 ])->execute();
             }
             if (isset($params['alternateIds']) && is_array($params['alternateIds'])) {
                 foreach ($params['alternateIds'] as $id) {
                     $this->db->createCommand()->insert('{{%zoo_items_elements}}',[
                         'element'=>'alternate',
                         'item_id'=>$item['id'],
                         'value_int'=>$id,
                     ])->execute();
                 }
             }
         }

         $this->dropColumn('{{%zoo_items}}','params');
         $this->execute("DELETE FROM {{%zoo_elements}} WHERE type = 'alternate'");

         $this->renameTable('{{%zoo_items}}','{{%items}}');
         $this->renameTable('{{%zoo_elements}}','{{%elements}}');
         $this->renameTable('{{%zoo_categories}}','{{%categories}}');
         $this->renameTable('{{%zoo_items_categories}}','{{%items_categories}}');
         $this->renameTable('{{%zoo_items_elements}}','{{%items_elements}}');
         $this->renameTable('{{%zoo_schedule}}','{{%schedule}}');

         $this->dropTable('{{%zoo_widgets}}');

         $this->renameTable('{{%zoo_elements_categories}}','{{%elements_categories}}');

         $this->renameColumn('{{%elements}}','admin_hint','hint');
         $this->execute("UPDATE {{%elements}} SET hint = null WHERE hint = '0'");

         $this->db->createCommand("UPDATE {{%elements}} SET type = 'string' WHERE type = 'textfield'")->execute();
         $this->db->createCommand("UPDATE {{%elements}} SET type = 'integer' WHERE type = 'textfield_int'")->execute();

         $this->addColumn('{{%items}}', 'parent_category_id', $this->integer()->unsigned());

         $this->addColumn('{{%categories}}','subtitle', $this->string());
         $this->addColumn('{{%categories}}','image', $this->string());
         $this->addColumn('{{%categories}}','preview', $this->string());
         $this->addColumn('{{%categories}}','quote', $this->string());
         $this->addColumn('{{%categories}}','content', $this->text());
         $this->addColumn('{{%categories}}','intro', $this->text());
         $this->addColumn('{{%categories}}','meta_title', $this->string());
         $this->addColumn('{{%categories}}','meta_description', $this->text());;
         $this->addColumn('{{%categories}}','meta_keywords', $this->string());

         $categories = (new \yii\db\Query())
             ->select(['id','params'])
             ->from('{{%categories}}')
             ->all();

         foreach ($categories as $category) {
             $params = $category['params'] ? \yii\helpers\Json::decode($category['params']) : null;
             $this->db->createCommand()->update('{{%categories}}', [
                 'subtitle'=>$params['subtitle']??null,
                 'image'=>$params['image']??null,
                 'preview'=>$params['preview']??null,
                 'quote'=>$params['quote']??null,
                 'content'=>$params['content']??null,
                 'intro'=>$params['intro']??null,
                 'meta_title'=>$params['metaTitle']??null,
                 'meta_description'=>$params['metaDescription']??null,
                 'meta_keywords'=>$params['metaKeywords']??null,
             ],
                 ['id'=>$category['id']])->execute();

         }

        $this->alterColumn('{{%categories}}', 'id', $this->integer()->unsigned()->notNull().' AUTO_INCREMENT');
        $this->alterColumn('{{%elements}}', 'id', $this->integer()->unsigned()->notNull().' AUTO_INCREMENT');
        $this->alterColumn('{{%items}}', 'id', $this->integer()->unsigned()->notNull().' AUTO_INCREMENT');
        $this->alterColumn('{{%items_elements}}', 'id', $this->integer()->unsigned()->notNull().' AUTO_INCREMENT');

        $this->alterColumn('{{%elements_categories}}', 'element_id', $this->integer()->unsigned()->notNull());
        $this->alterColumn('{{%elements_categories}}', 'category_id', $this->integer()->unsigned()->notNull());

        $this->alterColumn('{{%categories}}', 'parent_id', $this->integer()->unsigned());
        $this->alterColumn('{{%items}}', 'parent_category_id', $this->integer()->unsigned());

        $this->alterColumn('{{%items_elements}}', 'item_id', $this->integer()->unsigned()->notNull());
        $this->alterColumn('{{%items_categories}}', 'item_id', $this->integer()->unsigned()->notNull());
        $this->alterColumn('{{%items_categories}}', 'category_id', $this->integer()->unsigned()->notNull());

        $this->createTable('{{%categories_alternates}}', [
            'category_id' => $this->integer()->unsigned()->notNull(),
            'alternate_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-categories_alternates', '{{%categories_alternates}}', ['category_id', 'alternate_id']);

        $this->createTable('{{%items_alternates}}', [
            'item_id' => $this->integer()->unsigned()->notNull(),
            'alternate_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-items_alternates', '{{%items_alternates}}', ['item_id', 'alternate_id']);

        $categories = (new \yii\db\Query())
            ->select(['id', 'params'])
            ->from('{{%categories}}')
            ->all();

        foreach ($categories as $category) {
            $params = $category['params'] ? \yii\helpers\Json::decode($category['params']) : null;
            if (isset($params['alternateIds']) && is_array($params['alternateIds'])) {
                foreach ($params['alternateIds'] as $id) {
                    $this->db->createCommand()->insert('{{%categories_alternates}}', [
                        'category_id' => $category['id'],
                        'alternate_id' => $id
                    ])->execute();
                }
            }
        }

        $this->dropColumn('{{%categories}}','params');

        $items = (new \yii\db\Query())
            ->select(['item_id', 'value_int'])
            ->from('{{%items_elements}}')
            ->where(['element'=>'alternate'])
            ->all();

        foreach ($items as $item) {

            if (isset($item['value_int']) && $item['value_int'])

            $this->db->createCommand()->insert('{{%items_alternates}}', [
                'item_id' => $item['item_id'],
                'alternate_id' => $item['value_int'],
            ])->execute();

        }

        $this->db->createCommand()->delete('{{%items_elements}}',['element'=>'alternate'])->execute();

        $this->createTable('{{%applications_content}}', [
            'id' => $this->primaryKey()->unsigned(),
            'app_id'=>$this->string()->notNull(),
            'lang'=>$this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->text(),
            'content'=>$this->text(),
            'intro'=>$this->text(),
            'created_at'=>$this->integer()->unsigned()->notNull(),
            'updated_at'=>$this->integer()->unsigned()->notNull(),
        ], $tableOptions); */

        $this->renameTable('{{%zoo_config}}','{{%config}}');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171204_041121_upgrade_from_older_zoo cannot be reverted.\n";

        return false;
    }

}
