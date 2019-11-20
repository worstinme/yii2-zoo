<?php

namespace worstinme\zoo\elements\schedule;

use Yii;
use yii\db\Query;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{

    public function rules()
    {
        return [
            [$this->attribute, 'safe'],
        ];
    }

    public function getMultiple()
    {
        return true;
    }

    public $value_field = 'value_int';

    public function getValue()
    {
        $schedules = $this->owner->app->getSchedules();

        $v = [];

        $values = parent::getValue();

        foreach ($values as $val) {
            if (isset($schedules[$val])) {
                $sc = $schedules[$val];
                $sc['start_at'] = str_pad($sc['start_at'], 4, "0", STR_PAD_LEFT);
                $sc['finish_at'] = str_pad($sc['finish_at'], 4, "0", STR_PAD_LEFT);
                $v[] = $sc;
            }
        }

        return $v;
    }

    public function setValue($value)
    {
        parent::setValue($this->rawValue($value));
    }

    protected function rawValue($value) {

        $values = [];

        if (is_array($value)) {

            foreach ($value as $v) {

                if (!empty($v['mo']) || !empty($v['tu']) || !empty($v['we']) || !empty($v['th']) || !empty($v['fr']) || !empty($v['sa']) || !empty($v['su'])) {


                    $start_at = (int) str_replace(":", "", $v['start_at']);
                    $finish_at = (int) str_replace(":", "", $v['finish_at']);

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
                ->from('{{%schedule}}')
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

                Yii::$app->db->createCommand()->insert('{{%schedule}}', [
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

                $q = Yii::$app->db->getLastInsertID();

            }

            $va[] = $q;

        }
        return $va;
    }

    public function afterSave($insert)
    {
        if ($this->isAttributeActive && !$this->owner->hasAttribute($this->ownerAttribute) && !$this->owner->hasAttribute($this->ownColumn)) {
            $this->saveElement($this->rawValue($this->getValue()));
        }
    }
}
