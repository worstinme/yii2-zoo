<?php

namespace worstinme\zoo\widgets\models;

use Yii;

class Review extends \yii\base\Model
{
    public $title;
    public $subtitle;
    public $content;
    public $image;

    public static function getName() {
        return 'Review';
    }

    public static function getDescription() {
        return 'Review widget';
    }

    public static function getFormView() {
        return '@worstinme/zoo/widgets/forms/review';
    }

    public function rules()
    {
        return [
            [['content'],'string'],
            [['title','image','subtitle'],'string','max'=>255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('backend', 'Html & text'),
            'image' => Yii::t('backend', 'Url to photo'),
            'title' => Yii::t('backend', "Reviewer's name"),
            'subtitle' => Yii::t('backend', 'Short description'),
        ];
    }

}
