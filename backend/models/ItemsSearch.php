<?php

namespace worstinme\zoo\backend\models;

use worstinme\zoo\elements\BaseElementBehavior;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ItemsSearch represents the model behind the search form about `worstinme\zoo\models\Items`.
 */
class ItemsSearch extends Items
{

    public $search;
    public $query;
    public $withoutCategory;
    public $language;

    public $isSearch = true;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['search'],'safe'],
            [['category'],'safe'],
            ['withoutCategory','integer'],
            [['language'],'string'],
        ];

        foreach ($this->getBehaviors() as $behavior) {
            if (is_a($behavior, BaseElementBehavior::className())) {
                /** @var $behavior BaseElementBehavior */
                $rules = array_merge($rules, $behavior->rules());
            }
        }

        return $rules;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'search' => Yii::t('zoo', 'Поиск'),
            'withoutCategory'=>'Показать материалы без категорий'
        ]);

    }

    public function search($params)
    {
        
        $this->load($params);

        if ($this->withoutCategory) {
            $this->query = Items::find()->where(['app_id' => $this->app_id ]);
            $this->query->andWhere('id NOT IN (SELECT DISTINCT item_id FROM {{%items_categories}} WHERE category_id > 0)');
        }
        elseif (!empty($this->category) && count($this->category)) {
            $this->query = Items::find();
            $this->query->leftJoin(['category'=>'{{%items_categories}}'], "category.item_id = ".Items::tablename().".id");
            $this->query->andFilterWhere(['category.category_id'=>$this->category]);
        }
        else {
            $this->query = Items::find()->where([Items::tablename().'.app_id' => $this->app_id ]);
        }

        $this->query->andFilterWhere(['LIKE',Items::tablename().'.name',$this->search]);
        $this->query->andFilterWhere([Items::tablename().'.lang'=>$this->language]);

        $query = clone $this->query;

        $query->orderBy('created_at DESC, updated_at DESC');

        return $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy(Items::tablename().'.id'),
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per-page',30),
            ],
        ]);
    }

    public function itemIds($params)
    {
        $this->load($params);

        if ($this->withoutCategory) {
            $query = Items::find()->select(Items::tablename().'.id')->where([Items::tablename().'.app_id' => $this->app_id ]);
            $query->andWhere(Items::tablename().'.id NOT IN (SELECT DISTINCT item_id FROM {{%items_categories}} WHERE category_id > 0)');
        }
        elseif (!empty($this->category) && count($this->category)) {
            $query = Items::find()->select(Items::tablename().'.id');
            $query->leftJoin(['category'=>'{{%items_categories}}'], "category.item_id = ".Items::tablename().".id");
            $query->andFilterWhere(['category.category_id'=>$this->category]);
        }
        else {
            $query = Items::find()->select(Items::tablename().'.id')->where([Items::tablename().'.app_id' => $this->app_id ]);
        }

        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes) && $element->filter) {
                
                if ((!is_array($this->$e) && $this->$e !== null) || (is_array($this->$e) && count($this->$e) > 0)) {

                    $query->leftJoin([$e=>'{{%items_elements}}'], $e.".item_id = ".Items::tablename().".id AND ".$e.".element = '".$e."'");
                    $query->andFilterWhere([$e.'.value_string'=>$this->$e]);
                }

            }
        }

        $query->andFilterWhere(['LIKE',Items::tablename().'.name',$this->search]);

        return $query->groupBy(Items::tablename().'.id')->column();
    }

    public function formName()
    {
        return '';
    }
}
