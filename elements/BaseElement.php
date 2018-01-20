<?php

namespace worstinme\zoo\elements;

use worstinme\zoo\backend\models\Items;
use worstinme\zoo\backend\models\ItemsElements;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class BaseElement extends \yii\db\ActiveRecord
{
    protected $categories;

    public static function tableName()
    {
        return '{{%elements}}';
    }

    public $elementsStore = 'value_string';
    public $itemsStore;

    public function rules()
    {
        $rules = [

            [['name', 'type', 'label'], 'required'],
            [['name', 'type', 'label'], 'string', 'max' => 255],
            [['hint'], 'string'],
            ['name', 'match', 'pattern' => '#^[\w_]+$#i'],
            ['type', 'match', 'pattern' => '#^[\w_]+$#i'],
            [['name', 'app_id'], 'unique', 'targetAttribute' => ['name', 'app_id']],
            ['categories', 'each', 'rule' => ['integer']],//,'when' => function($model) { return $model->all_categories == 0; }, ],
            ['all_categories', 'default', 'value' => 1],
            [['filter', 'admin_filter', 'required', 'all_categories', 'refresh', 'sorter', 'own_column'], 'integer'],
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
        ];

        if (isset($this->labels) && count($this->labels)) {
            return ArrayHelper::merge($labels, $this->labels);
        } else {
            return $labels;
        }
    }

    public static function instantiate($row)
    {
        foreach (Yii::$app->zoo->elementsPaths as $namespace => $path) {
            $class = $namespace . '\\' . $row['type'] . '\Element';
            if (class_exists($class)) {
                return new $class;
            }
        }

        return new static();
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
        return Yii::$app->zoo->getApplication($this->app_id);
    }

    public function renderParams($params)
    {
        return '';
    }

    //сделать множественным
    public function setRelated($related)
    {
        $params = !empty($this->params) ? Json::decode($this->params) : [];;
        $params['related'] = $related;
        return $this->params = Json::encode($params);
    }

    //сделать множественным
    public function getRelated()
    {
        return null;
    }

    public function getAttributeName()
    {
        return 'element_' . $this->name;
    }

    public function afterFind()
    {
        $this->categories = (new \yii\db\Query())
            ->select('category_id')
            ->from('{{%elements_categories}}')
            ->where(['element_id' => $this->id])
            ->column();
        parent::afterFind();
    }

    // TODO :  заменить search на text_index

    //categories
    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($array)
    {
        $this->categories = $array;
    }

    public function getParamsArray()
    {
        return !empty($this->params) ? Json::decode($this->params) : [];
    }

    public function setParamsArray($array)
    {
        return $this->params = Json::encode($array);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $db = Yii::$app->db;

        if (!$insert) {
            $db->createCommand()->delete('{{%elements_categories}}', ['element_id' => $this->id])->execute();
        }

        if ($this->itemsStore !== null && array_key_exists('own_column', $changedAttributes) && $this->own_column != $changedAttributes['own_column']) {

            $table = Yii::$app->db->schema->getTableSchema(Items::tableName());
            $name = $this->prepareStoreName($this->name);

            if ($this->own_column) {
                if (!isset($table->columns[$name])) {
                    Yii::$app->db->createCommand()->addColumn(Items::tableName(), $name, $this->itemsStore)->execute();
                    $elements = ItemsElements::find()
                        ->select(['ie.item_id', 'ie.' . $this->elementsStore])
                        ->from(['ie' => ItemsElements::tableName()])
                        ->leftJoin(['e' => BaseElement::tableName()], 'e.name = ie.element')
                        ->where(['element' => $this->name, 'e.app_id' => $this->app_id])
                        ->all();
                    foreach ($elements as $element) {
                        Yii::$app->db->createCommand()->update(Items::tableName(), [$name => $element[$this->elementsStore]], ['id' => $element['item_id']])->execute();
                    }
                    Yii::$app->db->createCommand()->createIndex('fx-' . $name, Items::tableName(), $name)->execute();
                    Yii::$app->db->createCommand()->delete(ItemsElements::tableName(), ['element' => $this->name])->execute();
                }
            } else {

                if (isset($table->columns[$name])) {

                    $items = Items::find()
                        ->select(['id', $name])
                        ->where(['app_id' => $this->app_id])
                        ->asArray()
                        ->all();

                    foreach ($items as $item) {

                        Yii::$app->db->createCommand()->insert(ItemsElements::tableName(), [
                            'item_id' => $item['id'],
                            'element' => $this->name,
                            $this->elementsStore => $item[$name],
                        ])->execute();

                    }

                    Yii::$app->db->createCommand()->dropColumn(Items::tableName(), $name)->execute();

                }
            }

            Yii::$app->db->getSchema()->refresh();
        }

        if ($this->all_categories) {
            $db->createCommand()->insert('{{%elements_categories}}', [
                'element_id' => $this->id,
                'category_id' => 0,
            ])->execute();
        } elseif (is_array($this->categories) && count($this->categories)) {
            foreach ($this->categories as $category) {
                $db->createCommand()->insert('{{%elements_categories}}', [
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
        if ($this->itemsStore !== null && $this->own_column) {
            $table = Yii::$app->db->schema->getTableSchema(Items::tableName());
            $name = $this->prepareStoreName($this->name);
            if (isset($table->columns[$name])) {
                Yii::$app->db->createCommand()->dropColumn(Items::tableName(), $name)->execute();
            }
        }
        Yii::$app->db->createCommand()->delete('{{%elements_categories}}', ['element_id' => $this->id])->execute();
    }

    protected function prepareStoreName($name)
    {
        return Inflector::camel2id($this->app_id . '_' . $name, '_');
    }

    public function isActiveForModel($model)
    {
        // используется в ActiveField и в BaseElement
        if ($this->all_categories) {
            return true;
        }

        $categories = $this->getCategories();

        if (is_array($model->element_category))
        foreach ($model->element_category as $category) {
            if (in_array($category,$categories)) {
                return true;
            }
        }

        return false;
    }

    public function getIsAvailable() {
        return true;
    }

}