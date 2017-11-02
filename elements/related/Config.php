<?php

namespace worstinme\zoo\elements\related;

use worstinme\zoo\models\Applications;
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
            ['relatedApplication','integer'],
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
        return !empty($this->owner->paramsArray['relatedCategories'])?$this->owner->paramsArray['relatedCategories']:[];
    }

    public function setRelatedCategories($s){
        $params = $this->owner->paramsArray;
        if(is_array($s))
            foreach ($s as $key=>$value) {
                if (empty($value))
                    unset($s[$key]);
            }
        $params['relatedCategories'] = $s;
        return $this->owner->paramsArray = $params;
    }

    public function getRelatedApplication(){
        return !empty($this->owner->paramsArray['relatedApplication'])?$this->owner->paramsArray['relatedApplication']:null;
    }

    public function setRelatedApplication($s){
        $params = $this->owner->paramsArray;
        $params['relatedApplication'] = $s;
        return $this->owner->paramsArray = $params;
    }

    public function getViaTableName(){
        return !empty($this->owner->paramsArray['viaTableName'])?$this->owner->paramsArray['viaTableName']:null;
    }

    public function setViaTableName($s){
        $params = $this->owner->paramsArray;
        $params['viaTableName'] = $s;
        return $this->owner->paramsArray = $params;
    }

    public function getItems() {
        if ($this->relatedApplication) {
            return ArrayHelper::map(Items::find()->where([Items::tableName().'.app_id'=>$this->relatedApplication])->all(),'id','name');
        }
        return ArrayHelper::map( count($this->relatedCategories) ?
            Items::find()->joinWith(['categories'])->where(['category_id'=>$this->relatedCategories])->all() :
            Items::find()->joinWith(['categories'])->where([Items::tableName().'.app_id'=>$this->owner->app_id])->all()
            ,'id','name');
    }

}