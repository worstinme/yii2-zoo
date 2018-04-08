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
        if ($this->element->editor && !empty($value) && $this->element->app->app_id != Yii::$app->id) {
            $baseUrl = $this->element->app->baseUrl;
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
                    str_replace($old, $new, $value);
                }
                return parent::setValue($value);
            }
        }
        return parent::setValue($value);
    }

    public function getValue()
    {
        //добавляем хост приложения к ссылкам картинок если открываем материал в бэкенде
        if ($this->element->editor && !empty($value) && $this->element->app->app_id != Yii::$app->id) {
            $baseUrl = $this->element->app->baseUrl;
            $replaces = [];
            if (!empty($baseUrl)) {
                $doc = new \DOMDocument();
                $html = parent::getValue();
                $doc->loadHTML($html);
                $images = $doc->getElementsByTagName('img');
                foreach ($images as $img) {
                    $url = $img->getAttribute('src');
                    if (strpos($url,"/") === 0 && strpos($url,"//") !== 0) {
                        $img->setAttribute('src', $baseUrl.$url);
                        $replaces[$url] = $baseUrl.$url;
                    }
                }
                foreach ($replaces as $old => $new) {
                    str_replace($old, $new, $html);
                }
                return $html;
            }
        }
        return parent::getValue();
    }

}