<?php

namespace worstinme\zoo\models;

use Yii;

/**
 * This is the model class for table "{{%applications_content}}".
 *
 * @property integer $id
 * @property string $app_id
 * @property string $lang
 * @property string $name
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $content
 * @property string $intro
 * @property integer $created_at
 * @property integer $updated_at
 */
class ApplicationsContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%applications_content}}';
    }

    public function getApp() {
        return Yii::$app->zoo->getApplication($this->app_id);
    }

    public function getUrl() {
        return ['/' . $this->app->id . '/index', 'lang' =>$this->lang];
    }

    public function getAlternates() {
        return ApplicationsContent::find()->where(['app_id'=>$this->app_id])->andWhere(['<>','lang',$this->lang])->all();
    }
}
