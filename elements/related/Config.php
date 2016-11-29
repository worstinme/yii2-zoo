<?php

namespace worstinme\zoo\elements\related;

use worstinme\zoo\models\Items;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Config extends \yii\base\Behavior
{

    public $iconClass = 'uk-icon-align-left';


    public function getRules()
    {
        return [
            ['relatedCategories', 'each', 'rule'=>['string','max'=>255]],
            ['viaTableName', 'string','max'=>255],
        ];
    }

    public function getLabels()
    {
        return [
            'categories' => Yii::t('backend', 'Категории для фильтрации'),
            'viaTableName'=>Yii::t('backend','Связующая таблица'),
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/related/_settings';
    }

    public function getRelatedCategories(){
        return !empty($this->owner->params['relatedCategories'])?$this->owner->params['relatedCategories']:[];
    }

    public function setRelatedCategories($s){
        $params = $this->owner->params;
        if(is_array($s))
        foreach ($s as $key=>$value) {
            if (empty($value))
                unset($s[$key]);
        }
        $params['relatedCategories'] = $s;
        return $this->owner->params = $params;
    }

    public function getViaTableName(){
        return !empty($this->owner->params['viaTableName'])?$this->owner->params['viaTableName']:null;
    }

    public function setViaTableName($s){
        $params = $this->owner->params;
        $params['viaTableName'] = $s;
        return $this->owner->params = $params;
    }

    public function getItems() {
        $items = ArrayHelper::map(Items::find()->joinWith(['categories'])->where(['category_id'=>$this->relatedCategories])->all(),'id','name');

        return $items;
    }

}