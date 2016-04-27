<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class WidgetsSearch extends Widgets
{

    public function rules()
    {
        return [
            [['position', 'type', 'name', 'bound'], 'string','max'=>255],
        ];
    }

    public function query() {
        return Widgets::find();
    }

    public function search($params)
    {
        $query = Widgets::find();

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
            'position' => $this->position,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'bound', $this->bound]);

        return $dataProvider;
    }
}
