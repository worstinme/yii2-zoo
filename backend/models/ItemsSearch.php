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
    public $query;
    public $withoutCategory;

    public $isSearch = true;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['user_id','integer'],
            [['search'],'safe'],
            [['category'],'safe'],
            ['withoutCategory','integer'],
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

    public function attributeLabels()
    {
        $labels = [
            'search' => Yii::t('backend', 'Поиск'),
            'withoutCategory'=>'Показать материалы без категорий'
        ];

        foreach ($this->elements as $key => $element) {
            $labels[$key] = $element->label;
        }

        return $labels;
    }

    public function search($params)
    {
        
        $this->load($params);

        if ($this->withoutCategory) {
            $this->query = Items::find()->where(['i.app_id' => $this->app_id ]);
            $this->query->andWhere('i.id NOT IN (SELECT DISTINCT item_id FROM {{%zoo_items_categories}} WHERE category_id > 0)');
        }
        elseif (!empty($this->category) && count($this->category)) {
            $this->query = Items::find()->from(['i'=>'{{%zoo_items}}']);
            $this->query->leftJoin(['category'=>'{{%zoo_items_categories}}'], "category.item_id = i.id");
            $this->query->andFilterWhere(['category.category_id'=>$this->category]);
        }
        else {
            $this->query = Items::find()->where(['i.app_id' => $this->app_id ]);
        }

        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes()) && $element->filter) {
                
                if ((!is_array($this->$e) && $this->$e !== null) || (is_array($this->$e) && count($this->$e) > 0)) {

                    $this->query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = i.id AND ".$e.".element = '".$e."'");
                    $this->query->andFilterWhere([$e.'.value_string'=>$this->$e]);
                }

            }
        }

        $this->query->andFilterWhere(['LIKE','i.name',$this->search]);
        $this->query->andFilterWhere(['i.user_id'=>$this->user_id]);

        $query = clone $this->query;

        $query->orderBy('created_at DESC, updated_at DESC');

        return $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('i.id'),
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
    }

    public function itemIds($params)
    {
        

        $this->load($params);

        if ($this->withoutCategory) {
            $query = Items::find()->select('i.id')->where(['i.app_id' => $this->app_id ]);
            $query->andWhere('i.id NOT IN (SELECT DISTINCT item_id FROM {{%zoo_items_categories}} WHERE category_id > 0)');
        }
        elseif (!empty($this->category) && count($this->category)) {
            $query = Items::find()->select('i.id');
            $query->leftJoin(['category'=>'{{%zoo_items_categories}}'], "category.item_id = i.id");
            $query->andFilterWhere(['category.category_id'=>$this->category]);
        }
        else {
            $query = Items::find()->select('i.id')->from(['a'=>'{{%zoo_items}}'])->where(['i.app_id' => $this->app_id ]);
        }

        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes) && $element->filter) {
                
                if ((!is_array($this->$e) && $this->$e !== null) || (is_array($this->$e) && count($this->$e) > 0)) {

                    $query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = i.id AND ".$e.".element = '".$e."'");
                    $query->andFilterWhere([$e.'.value_string'=>$this->$e]);
                }

            }
        }

        $query->andFilterWhere(['LIKE','i.name',$this->search]);

        return $query->groupBy('i.id')->column();
    }
}
