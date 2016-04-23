<?php

namespace worstinme\zoo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use worstinme\zoo\models\ItemsItems;
use worstinme\zoo\models\Categories;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\frontend\models\Items`.
 */
class S extends Items
{
    public $search;
    public $query;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['search'], 'safe'],
        ];

        foreach ($this->elementsTypes as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['categories.id']);
    }


    public function search($params = null)
    {

        if ($params !== null) {
            $this->load($params);
        }

        $this->query = $this->app->getItems()->with(['itemsElements','app'])->joinWith(['categories']);
        
        $this->query->andFilterWhere([Categories::tablename().'.id'=>$this->categoryTree($this->category)]);

        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes()) && ($element->filter || $element->search)) {

                $value = $this->$e;

                if ((is_array($value) && count($value) > 0) || (!is_array($value) && !empty($value))) {

                    $this->query->leftJoin([$e=>ItemsElements::tablename()], $e.".item_id = i.id AND ".$e.".element = '".$e."'");
                    $this->query->andFilterWhere([$e.'.value_string'=>$value]);

                }

            }
        }

        if (!empty($this->search)) {
            $this->query->select('i.*, MATCH (node) AGAINST (:text) as REL')
                ->andWhere('MATCH (node) AGAINST (:text)',[':text'=>$this->search]);
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

        $sort = [
            'attributes' => [
                'name' => [
                    'asc' => ['i.name' => SORT_ASC],
                    'desc' => ['i.name' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'наменованию',
                ],
                'created' => [
                    'asc' => ['i.created_at' => SORT_ASC],
                    'desc' => ['i.created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'дате создания',
                ],
            ], 
            'defaultOrder'=>'created',   
        ];

        if (!empty($this->search)) {

            $sort['attributes']['rel'] = [
                'desc' => ['REL' => SORT_ASC],
                'asc' => ['REL' => SORT_DESC],
                'default' => SORT_ASC,
                'label'=>'релевантности',
            ];

            $sort['defaultOrder'] = ['rel' => SORT_ASC];
        } 

        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('i.id'),
            'sort'=>$sort, 
            'pagination'=>[
                'defaultPageSize'=>Yii::$app->controller->app->defaultPageSize,
            ],
        ]);

        return $dataProvider;
    }


    public function categoryTree($categories) {

        if (empty($categories)) {
            return null;
        }

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
