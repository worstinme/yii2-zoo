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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'user_id', 'flag', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params', 'alias', 'name', 'source'], 'safe'],
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

        $query = Items::find()->joinWith([
           // 'itemsElements',
        ])->groupBy('{{%zoo_items}}.id');

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

        return $dataProvider;
    }
}
