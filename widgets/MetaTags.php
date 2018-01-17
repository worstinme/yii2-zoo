<?php

namespace worstinme\zoo\widgets;

use Yii;
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\Items;
use yii\base\InvalidConfigException;
use yii\helpers\Url;

class MetaTags extends \yii\base\Widget
{
    public $model;
    public $title;
    public $keywords;
    public $description;
    public $alternates;
    public $canonical;

    public function init()
    {
        parent::init();

        if (!($this->model instanceof Items || $this->model instanceof Categories)) {
            throw new InvalidConfigException('Model should be instance of Items or Categories');
        }

    }

    public function run()
    {
        if ($this->title !== false) {
            $this->view->title = $this->title ? $this->title : $this->model->meta_title;
        }

        if ($this->keywords !== false) {
            $this->view->registerMetaTag(['name' => 'keywords', 'content' => $this->keywords ? $this->keywords : $this->model->meta_keywords]);
        }

        if ($this->description !== false) {
            $this->view->registerMetaTag(['name' => 'description', 'content' => $this->description ? $this->description : $this->model->meta_description]);
        }

        if ($this->canonical !== false) {
            $this->view->registerMetaTag(['rel' => 'canonical', 'href' => Url::to($this->model->url, true)]);
        }

        if ($this->alternates !== false) {

            if (count(Yii::$app->zoo->languages)) {

                $this->view->registerMetaTag(['rel' => 'alternate', 'hreflang' => $this->model->lang, 'href' => Url::to($this->model->url, true)]);

                foreach ($this->model->alternates as $alternate) {
                    $this->view->registerMetaTag(['rel' => 'alternate', 'hreflang' => $alternate->lang, 'href' => Url::to($alternate->url, true)]);
                }

            }

        }

    }
}