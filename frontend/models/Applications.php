<?php

namespace worstinme\zoo\frontend\models;

use Yii;


class Applications extends \yii\db\ActiveRecord
{
    private $param;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zoo_applications}}';
    }

    public function getParam() {
        if ($this->param === null) {
            $this->param = \yii\helpers\Json::decode($this->params);
        }
        return $this->param;
    }

    public function getTemplate($name = null) {
        $params = $this->getParam();
        return isset($params[$name]) ? $params[$name] : [];
    }

    public function getUrl() {
        return \yii\helpers\Url::toRoute(['/zoo/default/a','a'=>$this->name]);
    }
}
