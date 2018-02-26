<?php

namespace worstinme\zoo\backend\models;

use worstinme\zoo\elements\BaseElementBehavior;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;

class Items extends \worstinme\zoo\models\Items
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        $rules = [
            [['state','flag'],'boolean'],
           //
        ];

        foreach ($this->getBehaviors() as $behavior) {
            if (is_a($behavior, BaseElementBehavior::className())) {
                /** @var $behavior BaseElementBehavior */
                $rules = array_merge($rules, $behavior->rules(), $behavior->rulesRequired());
            }
        }

        return $rules;
    }

    public function attributeLabels()
    {
        $labels = [];
        if ($this->app) {
            foreach ($this->app->elements as $key => $element) {
                $labels[$key] = $element->label;
            }
            foreach ($this->app->systemElements as $key => $element) {
                $labels[$key] = $element->label;
            }
        }
        return $labels;
    }

    public function getRenderedElements()
    {
        $renderedElements = [];

        foreach ($this->behaviors as $behavior) {
            if (is_a($behavior, BaseElementBehavior::className())) {
                if ($behavior->isRendered) {
                    $renderedElements[] = $behavior->attribute;
                }
            }
        }

        return $renderedElements;
    }

}
