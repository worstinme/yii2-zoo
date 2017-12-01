<?php

namespace worstinme\zoo\backend\models;

use worstinme\zoo\models\Items;
use worstinme\zoo\models\ItemsElements;
use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

class Elements extends \worstinme\zoo\models\Elements
{

    private $categories = [];

    private $icon = 'uk-icon-plus';

    public static function tableName()
    {
        return '{{%zoo_elements}}';
    }

    public function rules()
    {
        $rules = [

            [['name', 'type', 'label'], 'required'],
            [['name', 'type', 'label'], 'string', 'max' => 255],

            [['admin_hint'], 'string'],

            ['name', 'match', 'pattern' => '#^[\w_]+$#i'],
            ['type', 'match', 'pattern' => '#^[\w_]+$#i'],

            [['name', 'app_id'], 'unique', 'targetAttribute' => ['name', 'app_id']],

            ['categories', 'each', 'rule' => ['integer']],//,'when' => function($model) { return $model->all_categories == 0; }, ],

            [['filter', 'admin_filter', 'required', 'all_categories', 'refresh', 'sorter', 'own_column', 'text_index'], 'integer'],

            [['related'], 'match', 'pattern' => '#^[\w_]+$#i'],

            [['params'], 'safe'],

        ];

        if (isset($this->rules) && count($this->rules)) {
            return ArrayHelper::merge($rules, $this->rules);
        } else {
            return $rules;
        }
    }

    public function attributeLabels()
    {
        $labels = [
            'id' => Yii::t('zoo', 'ID'),
            'title' => Yii::t('zoo', 'Название поля (Label)'),
            'name' => Yii::t('zoo', 'Системное название поля'),
            'required' => Yii::t('zoo', 'Обязательно для заполнения?'),
            'filter' => Yii::t('zoo', 'Использовать в фильтре?'),
            'params' => Yii::t('zoo', 'Params'),
            'placeholder' => Yii::t('zoo', 'Placeholder'),
            'categories' => Yii::t('zoo', 'Категории'),
            'all_categories' => Yii::t('zoo', 'Все категории'),
            'types' => Yii::t('zoo', 'Типы материалов'),
            'type' => Yii::t('zoo', 'Тип элемента'),
            'refresh' => Yii::t('zoo', 'Обновлять поле?'),
            'sorter' => 'Использовать поле в сортировке',
            'admin_hint' => Yii::t('zoo', 'Подсказка к полю в форме админки'),
            'own_column' => Yii::t('zoo', 'Выделить отдельную колонку'),
            'text_index' => Yii::t('zoo', 'Добавлять в fulltext index'),
        ];

        if (isset($this->labels) && count($this->labels)) {
            return ArrayHelper::merge($labels, $this->labels);
        } else {
            return $labels;
        }
    }

    public function getIcon()
    {
        if (isset($this->iconClass)) {
            return \yii\helpers\Html::i('', ['class' => $this->iconClass]);
        } else {
            return \yii\helpers\Html::i('', ['class' => $this->icon]);
        }
    }

    public function getApp()
    {
        return $this->hasOne(Applications::className(), ['id' => 'app_id']);
    }

    public function renderParams($params)
    {
        return '';
    }

    // TODO :  сделать множественным
    public function setRelated($related)
    {
        $params = !empty($this->params) ? Json::decode($this->params) : [];;
        $params['related'] = $related;
        return $this->params = Json::encode($params);
    }

    //categories
    public function getCategories()
    {
        if (!count($this->categories)) {
            $this->categories = (new \yii\db\Query())
                ->select('category_id')
                ->from('{{%zoo_elements_categories}}')
                ->where(['element_id' => $this->id])
                ->column();
        }
        return $this->categories;
    }

    public function setCategories($array)
    {
        $this->categories = $array;
    }


    public function afterSave($insert, $changedAttributes)
    {
        $db = Yii::$app->db;

        if (!$insert) {
            $db->createCommand()->delete('{{%zoo_elements_categories}}', ['element_id' => $this->id])->execute();
        }

        if (array_key_exists('own_column', $changedAttributes) && $this->own_column == 1 && $this->own_column != $changedAttributes['own_column']) {

            $table = Yii::$app->db->schema->getTableSchema(Items::tableName());

            if (!isset($table->columns[$this->name])) {

                Yii::$app->db->createCommand()->addColumn(Items::tableName(), $this->name, 'INTEGER(11)')->execute();

                $elements = ItemsElements::find()
                    ->select(['ie.item_id', 'ie.value_int'])
                    ->from(['ie' => ItemsElements::tableName()])
                    ->leftJoin(['e' => Elements::tableName()], 'e.name = ie.element')
                    ->where(['element' => $this->name, 'e.app_id' => $this->app_id])
                    ->all();

                foreach ($elements as $element) {
                    Yii::$app->db->createCommand()->update(Items::tableName(), [$this->name => $element['value_int']], ['id' => $element['item_id']])->execute();
                }

                Yii::$app->db->createCommand()->createIndex('fx-' . $this->name, Items::tableName(), $this->name)->execute();
                Yii::$app->db->createCommand()->delete(ItemsElements::tableName(), ['element' => $this->name])->execute();
                Yii::$app->db->getSchema()->refresh();

            }
        }

        if (array_key_exists('own_column', $changedAttributes) && $this->own_column == 0 && $this->own_column != $changedAttributes['own_column']) {

            $table = Yii::$app->db->schema->getTableSchema(Items::tableName());

            if (isset($table->columns[$this->name])) {

                $items = Items::find()
                    ->select(['id', $this->name])
                    ->where(['app_id' => $this->app_id])
                    ->asArray()
                    ->all();

                foreach ($items as $item) {

                    Yii::$app->db->createCommand()->insert(ItemsElements::tableName(), [
                        'item_id' => $item['id'],
                        'element' => $this->name,
                        'value_int' => $item[$this->name],
                    ])->execute();

                }

                Yii::$app->db->createCommand()->dropColumn(Items::tableName(), $this->name)->execute();
                Yii::$app->db->getSchema()->refresh();

            }

        }

        if ($this->all_categories) {
            $db->createCommand()->insert('{{%zoo_elements_categories}}', [
                'element_id' => $this->id,
                'category_id' => 0,
            ])->execute();
        } elseif (is_array($this->categories) && count($this->categories)) {
            foreach ($this->categories as $category) {
                $db->createCommand()->insert('{{%zoo_elements_categories}}', [
                    'element_id' => $this->id,
                    'category_id' => (int)$category,
                ])->execute();
            }
        }


        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()->delete('{{%zoo_elements_categories}}', ['element_id' => $this->id])->execute();

    }

}