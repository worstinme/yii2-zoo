<?php

namespace worstinme\zoo\backend;

use Yii;

class ApplicationsContent extends \worstinme\zoo\models\ApplicationsContent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'lang', 'name', 'created_at', 'updated_at'], 'required'],
            [['meta_description', 'content', 'intro'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['app_id', 'lang', 'name', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'app_id' => Yii::t('app', 'App ID'),
            'lang' => Yii::t('app', 'Lang'),
            'name' => Yii::t('app', 'Name'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'content' => Yii::t('app', 'Content'),
            'intro' => Yii::t('app', 'Intro'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
