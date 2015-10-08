<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo;

use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

class Controller extends \yii\web\Controller
{
	public function render($view, $params = [])
    {
    	$view = $this->findView($view);

    	return parent::render($view, $params);
    }
    

    public function findView($view) {
    	
    	if ($this->module->moduleViewPath !== null) {

    		$path  = Yii::getAlias($this->module->moduleViewPath).DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR.ltrim($view, '/').'.php';
    		
        	if (is_file($path)) {
        		return $this->module->moduleViewPath.DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR.$view;
        	}	
    	}
    	
    	return $this->module->moduleDefaultViewPath.DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR.$view;
    }	
}