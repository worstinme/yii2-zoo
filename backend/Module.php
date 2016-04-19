<?php

namespace worstinme\zoo\backend;

use Yii;
use worstinme\zoo\helpers\AppHelper;
use yii\helpers\Inflector;

class Module extends \yii\base\Module
{
    public $accessRoles;

    public $controllerNamespace = 'worstinme\zoo\backend\controllers';



    public function init()
    {
        parent::init();

        if (!Yii::$app->has('zoo')) {

            Yii::$app->set('zoo',[ 'class'=>'\worstinme\zoo\Zoo' ]);

        }


        $this->registerTranslations();      
    }

    public function registerTranslations()
	{
	    Yii::$app->i18n->translations['backend'] = [
	        'class' => 'yii\i18n\PhpMessageSource',
	        'sourceLanguage' => 'ru-RU',
	        'basePath' => '@worstinme/zoo/messages',
	    ];
	}

    public function getElements() {

        $files = AppHelper::findDirectories(Yii::getAlias('@worstinme/zoo/elements'));

        $files = array_unique(array_merge($files, AppHelper::findDirectories(Yii::getAlias(Yii::$app->zoo->elementsPath))));

        $elements = [];

        foreach ($files as $file) {
            $elements[$file] = Inflector::camel2words($file);
        }

        return $elements;

    }

    public function getRenderers() {

        $files = AppHelper::findDirectories(Yii::getAlias('@worstinme/zoo/renderers'));

        $files = array_unique(array_merge($files, AppHelper::findDirectories(Yii::getAlias(Yii::$app->zoo->renderersPath))));

        $renderers = [];

        foreach ($files as $file) {
            $renderers[$file] = Inflector::camel2words($file);
        }

        return $renderers;

    }

    public function getTemplates() {
        return [
            'form',
            'full',
            'teaser',
            'related',
            'submission',
        ];
    }

	public static function t($category, $message, $params = [], $language = null)
	{
	    return Yii::t('modules/zoo/' . $category, $message, $params, $language);
	}

}