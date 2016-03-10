<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use worstinme\zoo\backend\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\models\Items`.
 */
class ItemsSearch extends Items
{

    public $color;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','app_id', 'user_id', 'flag', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['name','color'],'string','max'=>255],
            [['params'], 'safe'],
        ];
    }


    public function search($params)
    {
        $query = Items::find()->groupBy('{{%zoo_items}}.id');


        if (Yii::$app->request->get('app') !== null) {
            $query  = $query->where(['{{%zoo_items}}.app_id'=>Yii::$app->request->get('app')]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
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
            'user_id' => $this->user_id,
            'flag' => $this->flag,
            'sort' => $this->sort,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
