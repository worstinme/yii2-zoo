<?php

namespace worstinme\zoo\widgets\models;

use Yii;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\Elements;

class LastItems extends \yii\base\Model
{
    public $sort;
    public $desc;
    public $flag;
    public $app_id;
    public $categories;
    public $template = 'related';

    public static function getName() {
        return 'Last Items';
    }

    public static function getDescription() {
        return 'Displays last items';
    }

    public static function getFormView() {
        return '@worstinme/zoo/widgets/forms/lastitems';
    }

    public function rules()
    {
        return [
            [['sort'],'string'],
            [['desc','flag','app_id'],'integer'],
            ['categories','each','rule'=>['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('backend', 'Html & text'),
        ];
    }

    public function getApplications() {
        return Applications::find()->select(['title'])->indexBy('id')->column();
    }

}
