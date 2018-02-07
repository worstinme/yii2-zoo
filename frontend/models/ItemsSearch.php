<?php

namespace worstinme\zoo\frontend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\frontend\models\Items`.
 */
class ItemsSearch extends Items
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

    /*    foreach ($this->elementsTypes as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
            }
        } */

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

        $this->query = self::find()
            ->with(['itemsElements'])
            ->joinWith(['categories'])
            ->where([self::tableName().'.app_id'=>$this->app_id,self::tableName().'.lang'=>$this->lang]);

     /*   foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes()) && ($element->filter || $e == $this->app->defaultOrder)) {

                $value = $this->$e;

                if ($e == $this->app->defaultOrder || ((is_array($value) && count($value) > 0) || (!is_array($value) && !empty($value)))) {

                    $this->query->leftJoin([$e=>ItemsElements::tablename()], $e.".item_id = ".Items::tablename().".id AND ".$e.".element = '".$e."'");
                    $this->query->andFilterWhere([$e.'.value_string'=>$value]);

                }

            }
        }

        if (!empty($this->search)) {
            $this->query->select(Items::tablename().'.*, MATCH (node) AGAINST (:text) as REL')
                ->andWhere('MATCH (node) AGAINST (:text)',[':text'=>$this->search]);
        } */

        return $this->query;
    }

    public function data($params) {

        $this->load($params);

        $query = $this->search();

        if (!$this->validate()) {
           return $query;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy(Items::tablename().'.id'),
        ]);

        return $dataProvider;
    }


    public function categoryTree($categories) {

        if (empty($categories)) {
            return null;
        }

        $related = (new \yii\db\Query())
                ->select('id')
                ->from('{{%categories}}')
                ->where(['parent_id'=>$categories,'state'=>1])
                ->column(); 

        if (count($related) > 0) {
            $related = $this->categoryTree($related);
        }

        return array_merge($categories,$related);
    }

}
