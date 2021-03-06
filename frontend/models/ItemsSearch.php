<?php

namespace worstinme\zoo\frontend\models;

use worstinme\zoo\elements\BaseElement;
use worstinme\zoo\elements\BaseElementBehavior;
use worstinme\zoo\models\ItemsElements;
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
            [['search','element_category'], 'safe'],
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

        $this->query = parent::find()
            ->with(['itemsElements'])
            ->joinWith(['categories'])
            ->where([self::tableName().'.app_id'=>$this->app_id,self::tableName().'.lang'=>$this->lang]);

        $this->query->andFilterWhere([Categories::tablename().'.id'=>$this->categoryTree($this->element_category)]);

        $this->query->andFilterWhere([self::tableName().'.flag'=>$this->flag]);
        $this->query->andFilterWhere([self::tableName().'.state'=>$this->state]);

        foreach ($this->getBehaviors() as $behavior) {

            if (is_a($behavior, BaseElementBehavior::className())) {
                /** @var $behavior BaseElementBehavior */

                if (is_a($behavior->element, BaseElement::className())) {
                    //TODO: own column join & filter support
                    if (!$behavior->element->own_column) {
                        $this->query->leftJoin([$behavior->ownerAttribute=>ItemsElements::tablename()], $behavior->ownerAttribute.".item_id = ".Items::tablename().".id AND ".$behavior->ownerAttribute.".element = '".$behavior->ownerAttribute."'");
                        $this->query->andFilterWhere([$behavior->ownerAttribute.'.'.$behavior->field=>$this->{$behavior->attribute}]);
                    }
                }

            }

        }

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




}
