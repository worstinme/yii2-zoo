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
use worstinme\zoo\models\Applications;
use yii\helpers\FileHelper;
use Yii;

class Controller extends \yii\web\Controller
{

    private $modelFields;
    private $fields;
    private $application;

	public function render($view, $params = [])
    {
    	$view = $this->findView($view);

        \worstinme\zoo\AdminAssets::register($this->view);

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


    public function getApplication($redirect = false) {
        
        $app = Yii::$app->request->get('app');

        $application = Applications::findOne($app);

        if ($application === null) {
            $application = Applications::find()->where(['name'=>$app])->one();
        }

        if ($application === null && $redirect) {
            Yii::$app->getSession()->setFlash('warning', Yii::t('admin','Приложение не существует'));
            return $this->redirect(['/'.$this->module->id.'/default/index']);
        }
        elseif($application === null) {
            return new Applications;
        }

        return $application;
    }

    public function getApp() {
        if ($this->application === null) {
            $this->application = $this->getApplication();
        }
        return $this->application;
    }

    public function getModelFields() {
        
        if (!count($this->modelFields)) {
            
            $fields = FileHelper::findFiles(Yii::getAlias('@worstinme/zoo/fields'),['only'=>['Fields.php']]);

            $models = [];

            foreach ($fields as $key => $value) {
                $parts = explode(DIRECTORY_SEPARATOR,$value);
                $path = '\worstinme\zoo\fields\\'.$parts[count($parts)-2].'\Fields';
                $class = new $path;
                $models[$class->alias] = $class;
            }

            $this->modelFields = $models;
        }

        return $this->modelFields;
    }



    public static function transliteration($str)
    {
        // ГОСТ 7.79B
        $transliteration = array(
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Cz', 'ц' => 'cz',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => 'ʺ', 'ъ' => 'ʺ',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e`',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '№' => '#', 'Ӏ' => '‡',
            '’' => '`', 'ˮ' => '¨',
        );

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z\-]/', '', $str);
        $str = preg_replace('|([-]+)|s', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

}