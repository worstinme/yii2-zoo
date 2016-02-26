<?php

namespace worstinme\zoo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use worstinme\zoo\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form about `worstinme\zoo\models\Categories`.
 */
class CategoriesSearch extends Categories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'parent_id', 'app_id', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['name', 'title', 'params'], 'safe'],
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
        $query = Categories::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'app_id' => $this->app_id,
            'sort' => $this->sort,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
