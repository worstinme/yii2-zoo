<?php

namespace worstinme\zoo;

use Yii;
use yii\helpers\FileHelper;
use worstinme\zoo\models\Applications;

class Zoo extends \yii\base\Component { 

	public $appsConfigPath = '@app/config/zoo';
	public $elementsPath = '@app/components/elements';
	public $renderersPath = '@app/components/renderers';

	private $menu;


	public function getMenu() {

		if ($this->menu === null) {
			$this->menu = new \worstinme\zoo\models\Menu;
		}

		return $this->menu;
	}
}