<?php

namespace worstinme\zoo;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use worstinme\zoo\models\Applications;
use worstinme\zoo\backend\models\Widgets;
use worstinme\zoo\backend\models\Config;

class Zoo extends \yii\base\Component { 

	public $appsConfigPath = '@app/config/zoo';
	public $elementsPath = '@app/components/elements';
	public $renderersPath = '@app/components/renderers';

	public $callbacks = [];

	private $menu;
	private $_widgets;


	public function getMenu() {

		if ($this->menu === null) {
			$this->menu = new \worstinme\zoo\models\Menu;
		}

		return $this->menu;
	}

	public function findShortcodes($content) {

		$shortcode = new \worstinme\zoo\helpers\ShortcodeHelper;
		$shortcode->callbacks = $this->callbacks();

		return $shortcode->parse($content);
	}

	public function callbacks() {
		
		return array_merge([
			'uk-slideshow'=>['worstinme\uikit\widgets\Slideshow','widget'],
			//'anothershortcode'=>function($attrs, $content, $tag){},
		],$this->callbacks);
	}

	public function config($name, $default = null) {

		if (($config = Config::find()->where(['name'=>$name])->one()) !== null) {
			return $config->value;
		}

		return $default;
	}

	public function renderWidgets($params) {

		$out = '';

		if (!empty($params['position'])) {

			$widgets = Widgets::find()->where(['position'=>$params['position']])->all();

			foreach ($widgets as $widget) {

				$out .= $this->callWidget($widget);

			}

		}

		return $out;

	}

	protected function callWidget($widget) {

		if (!empty($this->widgets[$widget->type])) {

			return call_user_func([$this->widgets[$widget->type],'widget'], $widget->getParams());

		}

	}

	protected function getWidgets() {

		if ($this->_widgets === null) {

	        $widgets = [];

	        $path = Yii::getAlias('@worstinme/zoo/widgets/');

	        $widgetModels = \yii\helpers\FileHelper::findFiles($path);

	        foreach ($widgetModels as $key => $model) {
	            $model = str_replace($path, '', rtrim($model,".php"));
	            $widgets[$model] = "worstinme\zoo\widgets\\".$model;
	        }

	        $this->_widgets = $widgets;

        }

        return $this->_widgets;
    }

}