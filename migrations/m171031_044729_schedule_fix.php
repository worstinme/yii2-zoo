<?php

use yii\db\Migration;

class m171031_044729_schedule_fix extends Migration
{
    public function safeUp()
    {
        $schedules = (new \yii\db\Query())
            ->select('*')
            ->from('{{zoo_schedule}}')
            ->where('start_at > finish_at')
            ->all();

        foreach($schedules as $schedule) {

            $this->db->createCommand()->update('{{zoo_schedule}}',[
                'finish_at'=>2400,
            ],[
                'id'=>$schedule['id'],
            ])->execute();


            $this->db->createCommand()->insert('{{zoo_schedule}}',[
                'mo'=>$schedule['su'],
                'tu'=>$schedule['mo'],
                'we'=>$schedule['tu'],
                'th'=>$schedule['we'],
                'fr'=>$schedule['th'],
                'sa'=>$schedule['fr'],
                'su'=>$schedule['sa'],
                'start_at'=>0,
                'finish_at'=>$schedule['finish_at'],
            ])->execute();

            $newScheduleId = $this->db->lastInsertID;

            $oldElements = (new \yii\db\Query())->select('ie.*')
                ->from(['ie'=>\worstinme\zoo\models\ItemsElements::tableName()])
                ->leftJoin(['e'=>\worstinme\zoo\models\Elements::tableName()],'e.name = ie.element')
                ->where(['e.type'=>'schedule'])
                ->andWhere(['ie.value_int'=>$schedule['id']])
                ->groupBy('ie.id')
                ->all();

            foreach ($oldElements as $element) {
                $this->db->createCommand()->insert(\worstinme\zoo\models\ItemsElements::tableName(),[
                    'item_id'=>$element['item_id'],
                    'element'=>$element['element'],
                    'value_int'=>$newScheduleId,
                ])->execute();
            }

        }

        $this->createIndex('fx-schedule','{{zoo_schedule}}',['mo','tu','we','th','fr','sa','su','start_at','finish_at']);
    }

    public function safeDown()
    {
        echo "m171031_044729_schedule_fix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171031_044729_schedule_fix cannot be reverted.\n";

        return false;
    }
    */
}
