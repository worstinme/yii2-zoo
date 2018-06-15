<?php

namespace worstinme\zoo\elements\related;

use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Items;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Element extends \worstinme\zoo\elements\BaseElement
{

    public $iconClass = 'uk-icon-align-left';
    public $_multiple = true;


    public function getRules()
    {
        return [
            ['relatedCategories', 'each', 'rule'=>['string','max'=>255]],
            ['relatedApplication','string'],
        ];
    }

    public function getLabels()
    {
        return [
            'categories' => Yii::t('backend', 'Категории для фильтрации'),
        ];
    }

    public function getConfigView() {
        return '@worstinme/zoo/elements/related/_settings';
    }

    public function getRelatedCategories(){
        return !empty($this->paramsArray['relatedCategories'])?$this->paramsArray['relatedCategories']:[];
    }

    public function setRelatedCategories($s){
        $params = $this->paramsArray;
        if(is_array($s))
            foreach ($s as $key=>$value) {
                if (empty($value))
                    unset($s[$key]);
            }
        $params['relatedCategories'] = $s;
        return $this->paramsArray = $params;
    }

    public function getRelatedApplication(){
        return !empty($this->paramsArray['relatedApplication'])?$this->paramsArray['relatedApplication']:null;
    }

    public function setRelatedApplication($s){
        $params = $this->paramsArray;
        $params['relatedApplication'] = $s;
        return $this->paramsArray = $params;
    }

    public function getItems() {
        if ($this->relatedApplication) {
            if (count($this->relatedCategories)) {
                ArrayHelper::map(Items::find()->joinWith(['categories'])->where(['category_id'=>$this->relatedCategories])->all(),'id','name');
            }
            return ArrayHelper::map(Items::find()->where([Items::tableName().'.app_id'=>$this->relatedApplication])->all(),'id','name');
        }
        return [];
    }

}