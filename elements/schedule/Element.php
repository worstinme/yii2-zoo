<?php

namespace worstinme\zoo\elements\schedule;

use Yii;
use yii\db\Query;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules($attributes)
    {
        return [
            [$attributes, 'safe'],
            //[$attributes,'required'],
        ];
    }

    public $multiple = true;

    public $value_field = 'value_int';

    public function LoadAttributesFromElements($attribute)
    {
        $value = [];
        foreach ($this->owner->itemsElements as $element) {
            if ($element->element == $attribute) {
                    $value[] = [
                        'id' => $element->id,
                        'value_text' => $element->value_text,
                        'value_int' => $element->value_int,
                        'value_string' => $element->value_string,
                        'value_float' => $element->value_float,
                    ];
            }
        }

        return $this->owner->values[$attribute] = $value;
    }

    public function getValue($attribute)
    {

        if (!isset($this->owner->values[$attribute])) {
            $this->loadAttributesFromElements($attribute);
        }

        $v = [];

        if (isset($this->owner->values[$attribute])) {

            $valu = $this->owner->values[$attribute];
            $schedules = $this->owner->app->schedules;

            foreach ($valu as $val) {

                if (isset($val[$this->value_field]) && isset($schedules[$val[$this->value_field]])) {

                    $v[] = $schedules[$val[$this->value_field]];

                }
            }

        }

        $v[] = ['mo' => 0, 'tu' => 0, 'we' => 0, 'th' => 0, 'fr' => 0, 'sa' => 0, 'su' => 0, 'start_at' => '0000', 'finish_at' => 2400];

        return $v;
    }

    public function setValue($attribute, $value)
    {

        if (!isset($this->owner->values[$attribute])) {
            $this->loadAttributesFromElements($attribute);
        }

        if (is_array($value)) {

            $va = [];

            foreach ($value as $key => $v) {

                if (!empty($v['mo']) || !empty($v['tu']) || !empty($v['we']) || !empty($v['th']) || !empty($v['fr']) || !empty($v['sa']) || !empty($v['su'])) {

                    $q = (new Query())
                        ->select('id')
                        ->from('{{%zoo_schedule}}')
                        ->where(['start_at'=>!empty($v['start_at']) ? $v['start_at'] : '0000'])
                        ->andWhere([
                            'mo'=>$v['mo']?1:0,
                            'tu'=>$v['tu']?1:0,
                            'we'=>$v['we']?1:0,
                            'th'=>$v['th']?1:0,
                            'fr'=>$v['fr']?1:0,
                            'sa'=>$v['sa']?1:0,
                            'su'=>$v['su']?1:0,
                            'start_at'=>$v['start_at'],
                            'finish_at'=>$v['finish_at'],
                        ])->scalar();

                    if ($q === false) {

                        Yii::$app->db->createCommand()->insert('{{%zoo_schedule}}',[
                            'mo'=>$v['mo']?1:0,
                            'tu'=>$v['tu']?1:0,
                            'we'=>$v['we']?1:0,
                            'th'=>$v['th']?1:0,
                            'fr'=>$v['fr']?1:0,
                            'sa'=>$v['sa']?1:0,
                            'su'=>$v['su']?1:0,
                            'start_at'=>$v['start_at'],
                            'finish_at'=>$v['finish_at'],
                        ])->execute();

                        $q = Yii::$app->db->lastInsertID;

                    }

                    $a = [
                        'value_text' => null,
                        'value_int' => null,
                        'value_string' => null,
                        'value_float' => null,
                    ];

                    $a[$this->value_field] = $q;

                    $va[] = $a;

                }
            }

            $this->owner->values[$attribute] = $va;

        }

        return true;
    }
}