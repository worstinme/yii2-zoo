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

                    $sc = $schedules[$val[$this->value_field]];

                    $sc['start_at'] = str_pad($sc['start_at'], 4, "0", STR_PAD_LEFT);
                    $sc['finish_at'] = str_pad($sc['finish_at'], 4, "0", STR_PAD_LEFT);

                    $v[] = $sc;

                }
            }

        }

        return $v;
    }

    public function setValue($attribute, $value)
    {

        if (!isset($this->owner->values[$attribute])) {
            $this->loadAttributesFromElements($attribute);
        }

        $values = [];

        if (is_array($value)) {

            foreach ($value as $v) {

                if (!empty($v['mo']) || !empty($v['tu']) || !empty($v['we']) || !empty($v['th']) || !empty($v['fr']) || !empty($v['sa']) || !empty($v['su'])) {


                    $start_at = (int)str_replace(":", "", $v['start_at']);
                    $finish_at = (int)str_replace(":", "", $v['finish_at']);

                    if ($start_at > $finish_at) {

                        $values[] = [
                            'mo' => $v['mo'] ? 1 : 0,
                            'tu' => $v['tu'] ? 1 : 0,
                            'we' => $v['we'] ? 1 : 0,
                            'th' => $v['th'] ? 1 : 0,
                            'fr' => $v['fr'] ? 1 : 0,
                            'sa' => $v['sa'] ? 1 : 0,
                            'su' => $v['su'] ? 1 : 0,
                            'start_at' => $start_at,
                            'finish_at' => 2400,
                        ];

                        $values[] = [
                            'mo' => $v['su'] ? 1 : 0,
                            'tu' => $v['mo'] ? 1 : 0,
                            'we' => $v['tu'] ? 1 : 0,
                            'th' => $v['we'] ? 1 : 0,
                            'fr' => $v['th'] ? 1 : 0,
                            'sa' => $v['fr'] ? 1 : 0,
                            'su' => $v['sa'] ? 1 : 0,
                            'start_at' => 0,
                            'finish_at' => $finish_at,
                        ];

                    } else {

                        $values[] = [
                            'mo' => $v['mo'] ? 1 : 0,
                            'tu' => $v['tu'] ? 1 : 0,
                            'we' => $v['we'] ? 1 : 0,
                            'th' => $v['th'] ? 1 : 0,
                            'fr' => $v['fr'] ? 1 : 0,
                            'sa' => $v['sa'] ? 1 : 0,
                            'su' => $v['su'] ? 1 : 0,
                            'start_at' => $start_at,
                            'finish_at' => $finish_at,
                        ];

                    }

                }

            }

        }

        $va = [];

        foreach ($values as $v) {

            $q = (new Query())
                ->select('id')
                ->from('{{%zoo_schedule}}')
                ->where([
                    'mo' => $v['mo'],
                    'tu' => $v['tu'],
                    'we' => $v['we'],
                    'th' => $v['th'],
                    'fr' => $v['fr'],
                    'sa' => $v['sa'],
                    'su' => $v['su'],
                    'start_at' => $v['start_at'],
                    'finish_at' => $v['finish_at'],
                ])->scalar();

            if (!$q) {

                Yii::$app->db->createCommand()->insert('{{%zoo_schedule}}', [
                    'mo' => $v['mo'],
                    'tu' => $v['tu'],
                    'we' => $v['we'],
                    'th' => $v['th'],
                    'fr' => $v['fr'],
                    'sa' => $v['sa'],
                    'su' => $v['su'],
                    'start_at' => $v['start_at'],
                    'finish_at' => $v['finish_at'],
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

        $this->owner->values[$attribute] = $va;

        return true;
    }
}
