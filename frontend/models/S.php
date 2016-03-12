<?php

namespace worstinme\zoo\frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use worstinme\zoo\frontend\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\frontend\models\Items`.
 */
class S extends Items
{
    public $price_min;
    public $price_max;
    public $search;
    public $color;
    public $material;
    public $filter;
    public $query;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_min', 'price_max'], 'integer','min'=>1],
            [['search'], 'safe'],
            [['color'], 'each','rule'=>['string']],
            [['material'],'string'],
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

    public function query()
    {
        return Items::find()->from(['a'=>'{{%zoo_items}}'])->where(['a.app_id' => $this->app_id ]);
    }



    public function search($params)
    {

        $this->query = $this->query();

        $sort = [
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
                'hits' => [
                    'asc' => ['a.hits' => SORT_ASC],
                    'desc' => ['a.hits' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'hits',
                ],
            ], 
            'defaultOrder'=>['price' => SORT_DESC],   
        ];

        

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            //return $dataProvider;
        }

        if ($this->price_max > 0 || $this->price_min > 0) {
            $this->query->andFilterWhere(['<=','price',$this->price_max]);
            $this->query->andFilterWhere(['>=','price',$this->price_min]);
        }

        $this->query->leftJoin(['color'=>'{{%zoo_items_elements}}'], "color.item_id = a.id AND color.element = 'color'");
        $this->query->leftJoin(['material'=>'{{%zoo_items_elements}}'], "material.item_id = a.id AND material.element = 'material'");
   /*     $query->leftJoin(['application'=>'{{%zoo_items_elements}}'], "application.item_id = a.id AND application.element = 'application'");
        $query->leftJoin(['article'=>'{{%zoo_items_elements}}'], "article.item_id = a.id AND article.element = 'article'");
*/
        if ($this->color !== null) {
            $this->query->andFilterWhere(['color.value_string'=>$this->color]);
        }

        if ($this->material !== null) {
            $this->query->andFilterWhere(['material.value_string'=>$this->material]);
        }

        $this->query->andFilterWhere([
            'id' => $this->id,
            'a.app_id' => $this->app_id,
            'user_id' => $this->user_id,
            'flag' => $this->flag,
            'sort' => $this->sort,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $this->query->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'source', $this->source]);

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query->groupBy('a.id'),
            'sort'=> $sort, 
            'pagination'=>[
                'pageSize'=>30,
            ],
        ]);

        return $dataProvider;
    }
}
