<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\Inflector;
use yii\base\InvalidParamException;

class Applications extends \worstinme\zoo\models\Applications
{

    public $example;

    public function rules()
    {
        return [
            ['name', 'required','when' => function($model) { return $model->isNewRecord;}, ],
            ['name', 'match', 'pattern' => '#^[\w_]+$#i','when' => function($model) { return $model->isNewRecord;}, ],
            ['name', 'unique', 'targetClass' => Applications::className(), 'message' => Yii::t('backend', 'Такое приложение уже есть')],
            ['name', 'string', 'min' => 2, 'max' => 255,'when' => function($model) { return $model->isNewRecord;}, ],
            ['name', 'controllerFileExists','when' => function($model) { return $model->isNewRecord;}, ],
            ['name', 'viewFolderExists','when' => function($model) { return $model->isNewRecord;}, ],

            ['model_table_name', 'match', 'pattern' => '#^[\w_}{\%}]+$#i'],
            ['model_table_name', 'tableExists','when' => function($model) { return $model->isNewRecord;}, ],
            ['model_table_name', 'modelFileExists','when' => function($model) { return $model->isNewRecord;}, ],
            ['example','exampleExists','when' => function($model) { return $model->isNewRecord;}, ],

            [['filters','itemsSearch','itemsSort','itemsColumns','defaultPageSize'],'integer'],
            
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            [['content','intro','metaDescription','metaKeywords'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Системное имя приложения'),
            'title' => Yii::t('backend', 'Название приложения'),
            'sort' => Yii::t('backend', 'Sort'),
            'state' => Yii::t('backend', 'State'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'params' => Yii::t('backend', 'Params'),
        ];
    }



    public function setTemplate($name,$template) {
        $params = !empty($this->templates) ? \yii\helpers\Json::decode($this->templates) : []; 
        $params[$name] = $template; 
        return $this->templates = \yii\helpers\Json::encode($params);
    }


    public function setMetaTitle($s) {
        $params = $this->params; $params['metaTitle'] = $s;
        return $this->params = $params;
    }

    public function setFilters($s) {
        $params = $this->params; $params['filters'] = $s;
        return $this->params = $params;
    }

    public function setMetaKeywords($s) {
        $params = $this->params; $params['metaKeywords'] = $s;
        return $this->params = $params;
    }

    public function setItemsSearch($s) {
        $params = $this->params; $params['itemsSearch'] = $s;
        return $this->params = $params;
    }

    public function setItemsSort($s) {
        $params = $this->params; $params['itemsSort'] = $s;
        return $this->params = $params;
    }

    public function setDefaultPageSize($s) {
        $params = $this->params; $params['defaultPageSize'] = $s;
        return $this->params = $params;
    }

    public function setItemsColumns($s) {
        $params = $this->params; $params['itemsColumns'] = $s;
        return $this->params = $params;
    }

    public function setMetaDescription($s) {
        $params = $this->params; $params['metaDescription'] = $s;
        return $this->params = $params;
    }
    public function create() {

        //controller 

        $controller = Yii::$app->view->render('@worstinme/zoo/applications/default/controllers/DefaultController',[
                'controllerName'=>$this->controllerName,
                'modelName'=>$this->modelName,
            ]);

        file_put_contents(Yii::getAlias('@app/controllers/'.$this->controllerName.'.php'),$controller);

        //views

        $controller = strtolower($this->name);

        if (!is_dir(Yii::getAlias('@app/views/'.$controller))) {
            mkdir(Yii::getAlias('@app/views/'.$controller));
        }

        $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@worstinme/zoo/applications/default/views'));

        foreach ($files as $file) {

            $view = str_replace([Yii::getAlias('@worstinme/zoo/applications/default/views/'),".php"],"",$file);

            $text = Yii::$app->view->render('@worstinme/zoo/applications/default/views/'.$view,[
                    'controller'=>$controller,
                ]);

            file_put_contents(Yii::getAlias('@app/views/'.$controller.'/'.$view).'.php',$text);

        }

      /*  if ($this->modelName !== null) {

            $model = Yii::$app->view->render('@worstinme/zoo/applications/default/models/Items',[
                'modelName'=>$this->modelName,
                'tableName'=>$this->model_table_name,
            ]);

            file_put_contents(Yii::getAlias('@app/models/'.$this->modelName.'.php'),$model);

            $searchModel = Yii::$app->view->render('@worstinme/zoo/applications/default/models/S',[
                'modelName'=>$this->modelName,
                'tableName'=>$this->model_table_name,
            ]);

            file_put_contents(Yii::getAlias('@app/models/Search'.$this->modelName.'.php'),$searchModel);

        } */

        return true;
    }

    public function tableExists($attribute, $params)
    {
        $tableSchema = Yii::$app->db->schema->getTableSchema($this->model_table_name);

        if ($tableSchema !== null) {
            $this->addError($attribute, 'Table already exists');
        }
        
    }

    public function modelFileExists($attribute, $params)
    {
        if (is_file(Yii::getAlias('@app/models/').$this->model_table_name.'.php')) {
            $this->addError($attribute, 'Model file already exists');
        }
        
    }

    public function exampleExists($attribute, $params)
    {
        if ($this->example === null) {
            $this->example = 'default';
        }

        if (!is_dir(Yii::getAlias('@worstinme/zoo/applications/').$this->example)) {
            $this->addError($attribute, 'Application example is not exists');
        }
        
    }

    public function getExamples() {

        $dirs = \worstinme\zoo\helpers\AppHelper::findDirectories(Yii::getAlias('@worstinme/zoo/applications'));

        $list = [];

        if (count($dirs)) {
            foreach ($dirs as $dir) {
               $list[$dir] = $dir;
            }
        }
        
        return $list;
    }

    public function controllerFileExists($attribute, $params)
    {
        if (is_file(Yii::getAlias('@app/controllers/').$this->controllerName.'.php')) {
            $this->addError($attribute, 'Controller file already exists');
        }
        
    }

    public function viewFolderExists($attribute, $params)
    {
        if (is_dir(Yii::getAlias('@app/views/').strtolower($this->name))) {
            $this->addError($attribute, 'View folder already exists');
        }
        
    }

    public function beforeSave($insert) {

        if (parent::beforeSave($insert)) {

            $this->params = Json::encode($this->params);

            if ($insert) {
                $this->templates = Json::encode(require(Yii::getAlias('@worstinme/zoo/applications/default/Templates.php')));
            }

            return true;
        }
        
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {   
        $this->params = Json::decode($this->params);

        if ($insert) {

            $this->create();

            $elements = require(Yii::getAlias('@worstinme/zoo/applications/default/Elements.php'));
            
            if (is_array($elements) && count($elements)) {
                foreach ($elements as $key => $params) {
                    
                    $element = new Elements;

                    $element->setAttributes($params);
                    $element->name = $key;
                    $element->app_id = $this->id;
                    $element->allcategories = 1;
                    $element->save();

                    print_r($element->errors);
                }
            }

        }

        return parent::afterSave($insert, $changedAttributes);
    } 

}