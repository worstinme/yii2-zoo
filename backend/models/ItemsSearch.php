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

    public $search;

    public $isSearch = true;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['search'],'string'],
        ];

        foreach ($this->elements_ as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
            }
        }

        return $rules;
    }

    public function attributeLabels()
    {
        $labels = [
            'search' => Yii::t('backend', 'Поиск'),
        ];

        foreach ($this->elements as $key => $element) {
            $labels[$key] = $element->title;
        }

        return $labels;
    }

    public function search($params)
    {
        $query = Items::find()->from(['a'=>'{{%zoo_items}}'])->where(['a.app_id' => $this->app_id ]);

        $this->load($params);

        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes) && $element->filterAdmin) {
                
                if ((!is_array($this->$e) && $this->$e !== null) || (is_array($this->$e) && count($this->$e) > 0)) {

                    $query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = a.id AND ".$e.".element = '".$e."'");
                    $query->andFilterWhere([$e.'.value_string'=>$this->$e]);
                }

            }
        }

        $query->andFilterWhere([
           
        ]);

        return $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('a.id'),
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
    }
}
