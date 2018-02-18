<?php
/**
 * Created by PhpStorm.
 * User: worst
 * Date: 18.02.2018
 * Time: 11:09
 */

namespace worstinme\zoo\behaviors;

use Yii;
use yii\db\ActiveRecord;

class ImageSrcHostBehavior extends \yii\base\Behavior
{
    public $attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'insertHost',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'removeHost',
        ];
    }

    public function removeHost()
    {
        foreach ($this->attributes as $attribute) {
            if ($this->owner->app->app_id != Yii::$app->id) {
                $baseUrl = rtrim($this->owner->app->baseUrl, "/");
                if (!empty($baseUrl)) {
                    $url = $this->owner->{$attribute};
                    if (strpos($url, $baseUrl) === 0) {
                        $this->owner->{$attribute} = substr($url, strlen($baseUrl));
                    }
                }
            }
        }
    }

    public function insertHost()
    {
        foreach ($this->attributes as $attribute) {
            //добавляем хост приложения к ссылкам картинок если открываем материал в бэкенде
            if ($this->owner->app->app_id != Yii::$app->id) {
                $baseUrl = rtrim($this->owner->app->baseUrl, "/");
                if (!empty($baseUrl)) {
                    $url = $this->owner->{$attribute};
                    if (strpos($url, "/") === 0 && strpos($url, "//") !== 0) {
                        $this->owner->{$attribute} = $baseUrl . $url;
                    }
                }
            }
        }
    }

}