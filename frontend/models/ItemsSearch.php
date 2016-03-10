<?php

namespace worstinme\zoo\frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use worstinme\zoo\frontend\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\frontend\models\Items`.
 */
class ItemsSearch extends Items
{
    public $price_min;
    public $price_max;
    public $search;
    public $color;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_min', 'price_max'], 'integer'],
            [['search'], 'safe'],
            [['color'], 'each','rule'=>['string']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Items::find();

        $sort = [
            'attributes' => [
                'name' => [
                    'asc' => ['{{%zoo_items}}.name' => SORT_ASC],
                    'desc' => ['{{%zoo_items}}.name' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'name',
                ],
                'price' => [
                    'asc' => ['{{%zoo_items}}.price' => SORT_ASC],
                    'desc' => ['{{%zoo_items}}.price' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'price',
                ],
                'hits' => [
                    'asc' => ['{{%zoo_items}}.hits' => SORT_ASC],
                    'desc' => ['{{%zoo_items}}.hits' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'hits',
                ],
            ], 
            'defaultOrder'=>['id' => SORT_DESC],   
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> $sort, 
            'pagination'=>[
                'pageSize'=>30,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->price_max > 0 || $this->price_min > 0 && $this->price_min != $this->price_max) {
            $query->andFilterWhere(['<=','price',$this->price_max]);
            $query->andFilterWhere(['>=','price',$this->price_min]);
        }

        if ($this->color !== null) {

        $query->innerJoin(['c'=>'{{%zoo_items_elements}}'], "c.item_id = {{%zoo_items}}.id AND element = 'color' AND c.value_string = :color",[':color'=>['белый']]);

        }


        $query->andFilterWhere([
            'id' => $this->id,
            '{{%zoo_items}}.app_id' => $this->app_id,
            'user_id' => $this->user_id,
            'flag' => $this->flag,
            'sort' => $this->sort,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'source', $this->source]);

       // print_r($query);

        return $dataProvider;
    }
}
