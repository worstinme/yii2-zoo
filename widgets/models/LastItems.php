<?php

namespace worstinme\zoo\widgets\models;

use Yii;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\Elements;

class LastItems extends \yii\base\Model
{
    public $sort;
    public $title;
    public $desc;
    public $limit;
    public $flag;
    public $app_id;
    public $categories;
    public $template = 'related';
    public $list_class;
    public $container_class;

    public static function getName() {
        return 'Last Items';
    }

    public static function getDescription() {
        return 'Displays last items';
    }

    public static function getFormView() {
        return '@worstinme/zoo/widgets/forms/lastitems';
    }

    public function rules()
    {
        return [
            [['sort','list_class','title','container_class'],'string'],
            [['desc','flag','app_id','limit'],'integer'],
            ['categories','each','rule'=>['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('backend', 'Html & text'),
        ];
    }

    public function getApplications() {
        return Applications::find()->select(['title'])->indexBy('id')->column();
    }

    public function getCatlist() {
        return !empty(Yii::$app->zoo->applications[$this->app_id])?Yii::$app->zoo->applications[$this->app_id]->catlist:[];
    }

    public function getElements() {
        $elements = (new \yii\db\Query())
            ->select(['{{%zoo_elements}}.label','{{%zoo_elements}}.name'])
            ->from('{{%zoo_elements}}')
            ->leftJoin(['c'=>'{{%zoo_elements_categories}}'],'c.element_id = {{%zoo_elements}}.id')
            ->where(['c.category_id'=>count($this->categories)?$this->categories:0,'app_id'=>$this->app_id])
            ->groupBy('{{%zoo_elements}}.name')
            ->all();

        $result = [];

        foreach ($elements as $key => $value) {
            $result[$value['name']] = $value['label'];
        }

        return $result;
    }

}
