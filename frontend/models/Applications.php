<?php

namespace worstinme\zoo\frontend\models;

use Yii;


class Applications extends \yii\db\ActiveRecord
{
    private $param_;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_applications}}';
    }

    public function getParam() {
        if ($this->param_ === null) {
            $this->param_ = \yii\helpers\Json::decode($this->params);
        }
        return $this->param_;
    }

    public function getTemplate($name) {
        return isset($this->param[$name]) ? $this->param[$name] : [];
    }

    public function getUrl() {
        return \yii\helpers\Url::toRoute(['/zoo/default/a','a'=>$this->name]);
    }

    //view Path
    public function getViewPath() { 
        return isset($this->param['viewPath'])?$this->param['viewPath']:''; 
    }

    //frontpage
    public function getFrontpage() { 
        return isset($this->param['frontpage'])?$this->param['frontpage']:''; 
    }
}
