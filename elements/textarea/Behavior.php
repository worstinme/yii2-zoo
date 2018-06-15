<?php

namespace worstinme\zoo\elements\textarea;

use Yii;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
    public $field ='value_text';

	public function rules()
	{
		return [
			[$this->attribute,'safe'],
		];
	}


    public function setValue($value)
    {
        // убираем хост приложения из ссылки к картинкам
        if ($this->element->editor && !empty($value) && Yii::$app->zoo->backend) {
            $baseUrl = Yii::getAlias($this->element->app->baseUrl !== null ? $this->element->app->baseUrl : Yii::$app->zoo->elfinder['baseUrl']);
            $replaces = [];
            if (!empty($baseUrl)) {
                $doc = new \DOMDocument();
                $doc->loadHTML($value);
                $images = $doc->getElementsByTagName('img');
                foreach ($images as $img) {
                    $url = $img->getAttribute('src');
                    if (strpos($url,$baseUrl) === 0) {
                        $replaces[$url] = substr($url,strlen($baseUrl));
                    }
                }
                foreach ($replaces as $old => $new) {
                    $value = str_replace($old, $new, $value);
                }
                return parent::setValue($value);
            }
        }
        return parent::setValue($value);
    }

    public function prepareTextareaValue($value) {
        //добавляем хост приложения к ссылкам картинок если открываем материал в бэкенде
        if ($this->element->editor && !empty($value) && Yii::$app->zoo->backend) {
            $baseUrl = Yii::getAlias($this->element->app->baseUrl !== null ? $this->element->app->baseUrl : Yii::$app->zoo->elfinder['baseUrl']);
            $replaces = [];
            if (!empty($baseUrl)) {
                $doc = new \DOMDocument();
                $doc->loadHTML($value);
                $images = $doc->getElementsByTagName('img');
                foreach ($images as $img) {
                    $url = $img->getAttribute('src');
                    if (strpos($url,"/") === 0 && strpos($url,"//") !== 0) {
                        $img->setAttribute('src', $baseUrl.$url);
                        $replaces[$url] = $baseUrl.$url;
                    }
                }
                foreach ($replaces as $old => $new) {
                    $value = str_replace($old, $new, $value);
                }
                return $value;
            }
        }
    }

}