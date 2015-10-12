<?php

namespace worstinme\zoo;

use Yii;
class Backend extends \yii\base\Module
{
    public $controllerNamespace = 'worstinme\zoo\controllers\backend';
    public $moduleViewPath = null;
    public $moduleDefaultViewPath = '@worstinme/zoo/views';

    public $itemTemplates = [
    	'form',
    	'full',
    	'teaser',
    	'related',
    ];
    
    public function init()
    {
        parent::init();

        $this->registerTranslations();      
    }

    public function registerTranslations()
	{
	    Yii::$app->i18n->translations['admin'] = [
	        'class' => 'yii\i18n\PhpMessageSource',
	        'sourceLanguage' => 'ru-RU',
	        'basePath' => '@worstinme/zoo/messages',
	    ];
	}

	

	public static function t($category, $message, $params = [], $language = null)
	{
	    return Yii::t('modules/zoo/' . $category, $message, $params, $language);
	}

}