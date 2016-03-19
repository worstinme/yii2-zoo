<?php

namespace worstinme\zoo\frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use worstinme\zoo\frontend\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\frontend\models\Items`.
 */
class S extends Items
{
    public $price_min;
    public $price_max;
    public $search;
    public $categories = [];
    public $query;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['price_min', 'price_max'], 'integer','min'=>1],
            [['search'], 'safe'],
        ];

        $elements = array_unique(ArrayHelper::getColumn($this->elements,'type'));

        foreach ($elements as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
            }
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */

    public function query()
    {

        return Items::find()->from(['a'=>'{{%zoo_items}}'])->leftJoin('{{%zoo_items_categories}}', "{{%zoo_items_categories}}.item_id = a.id")->andFilterWhere(['{{%zoo_items_categories}}.category_id'=>(new \yii\db\Query())->select('id')->from('{{%zoo_categories}}')->where(['app_id'=>$this->app_id,'state'=>1])->column()]);
    }

    public function search($params = null)
    {

        if ($params !== null) {
            $this->load($params);
        }

        $this->query = Items::find()->from(['a'=>'{{%zoo_items}}']);
        $this->query->leftJoin('{{%zoo_items_categories}}', "{{%zoo_items_categories}}.item_id = a.id");

        if (count($this->categories)) {
            $this->query->andFilterWhere(['{{%zoo_items_categories}}.category_id'=>$this->categoryTree($this->categories)]);
        }
        else {
            $this->query->andFilterWhere(['{{%zoo_items_categories}}.category_id'=>(new \yii\db\Query())
                ->select('id')
                ->from('{{%zoo_categories}}')
                ->where(['app_id'=>$this->app_id,'state'=>1])
                ->column()]);
        }

        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes()) && $element->filter) {

                $value = $this->$e;

                if ((is_array($value) && count($value) > 0) || (!is_array($value) && !empty($value))) {

                    $this->query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = a.id AND ".$e.".element = '".$e."'");
                    $this->query->andFilterWhere([$e.'.value_string'=>$value]);

                }

            }
        }

        return $this->query;
    }

    public function data($params) {

        $this->load($params);

        $query = $this->search();

        if (!$this->validate()) {
            //print_r($this->errors);
           // $query = $this->query();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('a.id'),
            'sort'=>[
                'attributes' => [
                    'name' => [
                        'asc' => ['a.name' => SORT_ASC],
                        'desc' => ['a.name' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'=>'name',
                    ],
                    'price' => [
                        'asc' => ['a.price' => SORT_ASC],
                        'desc' => ['a.price' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'=>'price',
                    ],
                    'amount' => [
                        'asc' => ['a.amount' => SORT_ASC],
                        'desc' => ['a.amount' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'=>'amount',
                    ],
                ], 
                'defaultOrder'=>['amount' => SORT_DESC],   
            ], 
            'pagination'=>[
                'defaultPageSize'=>36,
            ],
        ]);

        return $dataProvider;
    }


    public function categoryTree($categories) {

        $related = (new \yii\db\Query())
                ->select('id')
                ->from('{{%zoo_categories}}')
                ->where(['parent_id'=>$categories,'state'=>1])
                ->column(); 

        if (count($related) > 0) {
            $related = $this->categoryTree($related);
        }

        return array_merge($categories,$related);
    }

}
