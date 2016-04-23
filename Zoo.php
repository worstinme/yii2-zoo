<?php

namespace worstinme\zoo;

use Yii;
use yii\helpers\FileHelper;
use worstinme\zoo\models\Applications;
use worstinme\zoo\backend\models\Config;

class Zoo extends \yii\base\Component { 

	public $appsConfigPath = '@app/config/zoo';
	public $elementsPath = '@app/components/elements';
	public $renderersPath = '@app/components/renderers';

	public $widgets = [];

	private $menu;


	public function getMenu() {

		if ($this->menu === null) {
			$this->menu = new \worstinme\zoo\models\Menu;
		}

		return $this->menu;
	}

	public function findWidgets($content) {

		$shortcode = new \worstinme\zoo\helpers\ShortcodeHelper;
		$shortcode->callbacks = array_merge([
			'uk-slideshow'=>['worstinme\uikit\widgets\Slideshow','widget'],
			//'anothershortcode'=>function($attrs, $content, $tag){},
		],$this->widgets);

		return $shortcode->parse($content);
	}

	public function config($name, $default = null) {

		if (($config = Config::find()->where(['name'=>$name])->one()) !== null) {
			return $config->value;
		}

		return $default;
	}

}