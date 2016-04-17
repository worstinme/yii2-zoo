<?php echo "<?php"; ?>

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use worstinme\zoo\frontend\models\Categories;
use worstinme\zoo\frontend\models\Elements;
use app\models\<?=$modelName?> as Items;

class Search<?=$modelName?> extends Items
{
    public $price_min;
    public $price_max;
    public $_sklad_eu;
    public $_sklad_ru;
    public $_sklad_ar;
    public $minAmount;
    public $search;
    public $categories = [];
    public $_categories = [];
    public $query;

    public function rules()
    {
        $rules = [
            [['price_min', 'price_max'], 'integer','min'=>1],
            [['minAmount','_sklad_ru','_sklad_ar','_sklad_eu'],'integer'],

            [['categories','_categories'],'each','rule'=>['integer','skipOnEmpty'=>true]],

            [['search'], 'safe'],
        ];

        $elements = array_unique(ArrayHelper::getColumn($this->elements,'type'));

        foreach ($elements as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
            }
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */

    public function query()
    {

        return Items::find()->from(['a'=>$this::tableName()])->leftJoin('{{%zoo_items_categories}}', "{{%zoo_items_categories}}.item_id = a.id")->andFilterWhere(['{{%zoo_items_categories}}.category_id'=>(new \yii\db\Query())->select('id')->from('{{%zoo_categories}}')->where(['app_id'=>$this->app_id,'state'=>1])->column()]);
    }

    public function load($params, $formName = NULL) {


        if (isset($params['S']['categories']) && count($params['S']['categories'])) {
            foreach ($params['S']['categories'] as $key => $value) {
                if (empty($value)) {
                    unset($params['S']['categories'][$key]);
                }
            }

        }

        if (isset($params['S']['categories']) && count($params['S']['_categories'])) {
            foreach ($params['S']['_categories'] as $key => $value) {
                if (empty($value)) {
                    unset($params['S']['_categories'][$key]);
                }
            }
        }

        return parent::load($params, $formName);
    }

    public function search($params = null)
    {

        if ($params !== null) {
            $this->load($params);
        }

        $this->query = Items::find()->from(['a'=>$this::tableName()]);
        $this->query->leftJoin('{{%zoo_items_categories}}', "{{%zoo_items_categories}}.item_id = a.id");
        $this->query->andFilterWhere(['app_id'=>$this->app_id]);

        if (count($this->categories) > 0) {
            $categories = $this->categoryTree($this->categories);
        }
        else {
            $categories = (new \yii\db\Query())
                ->select('id')
                ->from('{{%zoo_categories}}')
                ->where(['app_id'=>$this->app_id,'state'=>1])
                ->column();
        }


        if (count($this->_categories) > 0) {

            $_categories = $this->categoryTree($this->_categories);

            foreach ($categories as $key => $category) {
                if (in_array($category, $_categories)) {
                    unset($categories[$key]);
                }
            }

            $this->query->andFilterWhere(['NOT IN','{{%zoo_items_categories}}.category_id',$_categories]);

        }


        $this->query->andFilterWhere(['{{%zoo_items_categories}}.category_id'=>$categories]);


        foreach ($this->elements as $element) {

            $e = $element->name;

            if (!in_array($e, $this->attributes()) && $element->filter) {

                $value = $this->$e;

                if ((is_array($value) && count($value) > 0) || (!is_array($value) && !empty($value))) {

                    $this->query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = a.id AND ".$e.".element = '".$e."'");
                    $this->query->andFilterWhere([$e.'.value_string'=>$value]);

                }

            }
        }

        if (!empty($this->search)) {
            $this->query->select('a.*, MATCH (node) AGAINST (:text) as REL')
                ->andWhere('MATCH (node) AGAINST (:text)',[':text'=>$this->search]);
        }

        $this->query->andFilterWhere(['<=','price',$this->price_max]);
        $this->query->andFilterWhere(['>=','price',$this->price_min]);
        $this->query->andFilterWhere(['>=','amount',$this->minAmount]);

        if ($this->_sklad_ar) {
            $e = 'sklad_ar';
            $this->query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = a.id AND ".$e.".element = '".$e."'");
            $this->query->andFilterWhere(['>',$e.'.value_string','0']);
        }

        if ($this->_sklad_ru) {
            $e = 'sklad_ru';
            $this->query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = a.id AND ".$e.".element = '".$e."'");
            $this->query->andFilterWhere(['>',$e.'.value_string','0']);
        }

        if ($this->_sklad_eu) {
            $e = 'sklad_eu';
            $this->query->leftJoin([$e=>'{{%zoo_items_elements}}'], $e.".item_id = a.id AND ".$e.".element = '".$e."'");
            $this->query->andFilterWhere(['>',$e.'.value_string','0']);
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
                    'asc' => ['a.name' => SORT_ASC],
                    'desc' => ['a.name' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'наменованию',
                ],
                'price' => [
                    'asc' => ['a.price' => SORT_ASC],
                    'desc' => ['a.price' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'цене',
                ],
                'amount' => [
                    'asc' => ['a.amount' => SORT_ASC],
                    'desc' => ['a.amount' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label'=>'количеству',
                ],
            ], 
            'defaultOrder'=>['amount' => SORT_DESC],   
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
            'query' => $query->groupBy('a.id'),
            'sort'=>$sort, 
            'pagination'=>[
                'defaultPageSize'=>36,
            ],
        ]);

        return $dataProvider;
    }


    public function categoryTree($categories) {

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
