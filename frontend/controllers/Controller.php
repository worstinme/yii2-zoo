<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\frontend\controllers;

use Yii;

use worstinme\zoo\frontend\models\Applications;

use yii\web\NotFoundHttpException;

class Controller extends \yii\web\Controller
{

	public function render($view, $app, $params = [])
    {
    	return parent::render($this->findView($view,$app), $params);
    }

    public function findView($view,$app) {
	
    	if ($app !== null && !empty($app->viewPath)) {

    		$path  = '@app'.DIRECTORY_SEPARATOR.trim($app->viewPath,"/").DIRECTORY_SEPARATOR.ltrim($view, '/');
    		
        	if (is_file(Yii::getAlias($path).'.php')) {
        		return $path;
        	}	
    	}
    	
    	return $view;
    }

}