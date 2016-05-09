<?php

namespace worstinme\zoo\widgets\models;

use Yii;
use worstinme\zoo\models\Items;

class UkSlideshow extends \yii\base\Model
{
    public $path;

    public static function getName() {
        return 'Uk-Slideshow';
    }

    public static function getDescription() {
        return 'Uikit Slideshow widget';
    }

    public static function getFormView() {
        return '@worstinme/zoo/widgets/forms/uk-slideshow';
    }

    public function rules()
    {
        return [
            [['path'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'path' => Yii::t('backend', 'Path to images folder'),
        ];
    }

}