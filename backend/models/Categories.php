<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

class Categories extends \worstinme\zoo\models\Categories
{

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'app_id'], 'required'],
            ['alias', 'match', 'pattern' => '#^[\w_-]+$#i'],
            [['parent_id', 'app_id', 'sort', 'state', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            [['name', 'alias','image'], 'string', 'max' => 255],
            [['metaDescription','metaKeywords','content','intro'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],

            //defaults
            ['state', 'default', 'value' => 1],
            ['parent_id', 'default', 'value' => 0],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'alias' => Yii::t('backend', 'Алиас'),
            'name' => Yii::t('backend', 'Название'),
            'parent_id' => Yii::t('backend', 'Родитель'),
            'app_id' => Yii::t('backend', 'App ID'),
            'sort' => Yii::t('backend', 'Sort'),
            'state' => Yii::t('backend', 'Опубликовано?'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'params' => Yii::t('backend', 'Params'),
            'preContent' =>  Yii::t('backend', 'Верхнее описание категории'),
            'content' =>  Yii::t('backend', 'Нижнее описание категории'),
            'image'=>Yii::t('backend', 'Изображение категории'),
        ];
    }


    public function setMetaTitle($s) {
        $params = $this->params; $params['metaTitle'] = $s;
        return $this->params = $params;
    }

    public function setImage($s) {
        $params = $this->params; $params['image'] = $s;
        return $this->params = $params;
    }

    public function setMetaKeywords($s) {
        $params = $this->params; $params['metaKeywords'] = $s;
        return $this->params = $params;
    }

    public function setMetaDescription($s) {
        $params = $this->params; $params['metaDescription'] = $s;
        return $this->params = $params;
    }

    public function setContent($s) {
        $params = $this->params; $params['content'] = $s;
        return $this->params = $params;
    }

    public function setIntro($s) {
        $params = $this->params; $params['intro'] = $s;
        return $this->params = $params;
    }

    public function afterDelete()
    {
        $db = Yii::$app->db;

        $db->createCommand()->delete('{{%zoo_items_categories}}', ['category_id'=>$this->id])->execute();
        $db->createCommand()->delete('{{%zoo_elements_categories}}', ['category_id'=>$this->id])->execute();
        $db->createCommand()->update('{{%zoo_categories}}',['parent_id'=>0], ['parent_id'=>$this->id])->execute();
        
        parent::afterDelete();
        
    }
}
