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
            ['name', 'required', 'when' => function ($model) {
                return $model->isNewRecord;
            },],
            ['name', 'match', 'pattern' => '#^[\w_]+$#i', 'when' => function ($model) {
                return $model->isNewRecord;
            },],
            ['name', 'unique', 'targetClass' => Applications::className(), 'message' => Yii::t('zoo', 'Такое приложение уже есть')],
            ['name', 'string', 'min' => 2, 'max' => 255, 'when' => function ($model) {
                return $model->isNewRecord;
            },],
            ['name', 'controllerFileExists', 'when' => function ($model) {
                return $model->isNewRecord;
            },],
            ['name', 'viewFolderExists', 'when' => function ($model) {
                return $model->isNewRecord;
            },],

            ['model_table_name', 'match', 'pattern' => '#^[\w_}{\%}]+$#i'],
            ['model_table_name', 'tableExists', 'when' => function ($model) {
                return $model->isNewRecord;
            },],
            ['model_table_name', 'modelFileExists', 'when' => function ($model) {
                return $model->isNewRecord;
            },],
            ['example', 'exampleExists', 'when' => function ($model) {
                return $model->isNewRecord;
            },],

            [['filters', 'itemsSearch', 'itemsSort', 'defaultOrderDesc', 'itemsColumns', 'defaultPageSize'], 'integer'],

            ['simpleItemLinks', 'integer'],

            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            ['app_alias', 'validateAppAlias'],

            [['content', 'intro', 'metaDescription', 'metaKeywords'], 'string'],
            [['metaTitle', 'defaultOrder'], 'string', 'max' => 255],
        ];
    }

    public function validateAppAlias($attribute, $params)
    {
        if (!is_dir(Yii::getAlias($this->$attribute . '/controllers/'))) {
            $this->addError($attribute, Yii::getAlias($this->$attribute . '/controllers/') . ' is not exist');
        }
        if (!is_dir(Yii::getAlias($this->$attribute . '/views/'))) {
            $this->addError($attribute, Yii::getAlias($this->$attribute . '/views/') . ' is not exist');
        }
        if (!is_dir(Yii::getAlias($this->$attribute . '/models/'))) {
            $this->addError($attribute, Yii::getAlias($this->$attribute . '/models/') . ' is not exist');
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('zoo', 'ID'),
            'name' => Yii::t('zoo', 'Системное имя приложения'),
            'title' => Yii::t('zoo', 'Название приложения'),
            'sort' => Yii::t('zoo', 'Sort'),
            'state' => Yii::t('zoo', 'State'),
            'created_at' => Yii::t('zoo', 'Created At'),
            'updated_at' => Yii::t('zoo', 'Updated At'),
            'params' => Yii::t('zoo', 'Params'),
        ];
    }

    public function setTemplate($name, $template)
    {
        $params = !empty($this->templates) ? \yii\helpers\Json::decode($this->templates) : [];
        $params[$name] = $template;
        return $this->templates = \yii\helpers\Json::encode($params);
    }


    public function setMetaTitle($s)
    {
        $params = $this->params;
        $params['metaTitle'] = $s;
        return $this->params = $params;
    }

    public function setFilters($s)
    {
        $params = $this->params;
        $params['filters'] = $s;
        return $this->params = $params;
    }

    public function setMetaKeywords($s)
    {
        $params = $this->params;
        $params['metaKeywords'] = $s;
        return $this->params = $params;
    }

    public function setItemsSearch($s)
    {
        $params = $this->params;
        $params['itemsSearch'] = $s;
        return $this->params = $params;
    }

    public function setItemsSort($s)
    {
        $params = $this->params;
        $params['itemsSort'] = $s;
        return $this->params = $params;
    }

    public function setDefaultPageSize($s)
    {
        $params = $this->params;
        $params['defaultPageSize'] = $s;
        return $this->params = $params;
    }

    public function setDefaultOrder($s)
    {
        $params = $this->params;
        $params['defaultOrder'] = $s;
        return $this->params = $params;
    }

    public function setDefaultOrderDesc($s)
    {
        $params = $this->params;
        $params['defaultOrderDesc'] = $s;
        return $this->params = $params;
    }

    public function setItemsColumns($s)
    {
        $params = $this->params;
        $params['itemsColumns'] = $s;
        return $this->params = $params;
    }

    public function setMetaDescription($s)
    {
        $params = $this->params;
        $params['metaDescription'] = $s;
        return $this->params = $params;
    }

    public function setSimpleItemLinks($s)
    {
        $params = $this->params;
        $params['simpleItemLinks'] = $s;
        return $this->params = $params;
    }

    public function create()
    {

        //controller 

        $controller = Yii::$app->view->render('@worstinme/zoo/applications/default/controllers/DefaultController', [
            'controllerName' => $this->controllerName,
            'modelName' => $this->modelName,
        ]);

        file_put_contents(Yii::getAlias('@app/controllers/' . $this->controllerName . '.php'), $controller);

        //views

        $controller = strtolower($this->name);

        if (!is_dir(Yii::getAlias('@app/views/' . $controller))) {
            mkdir(Yii::getAlias('@app/views/' . $controller));
        }

        $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@worstinme/zoo/applications/default/views'));

        foreach ($files as $file) {

            $view = str_replace([Yii::getAlias('@worstinme/zoo/applications/default/views'), ".php"], "", $file);

            $text = file_get_contents(Yii::getAlias('@worstinme/zoo/applications/default/views' . $view) . '.php');

            file_put_contents(Yii::getAlias('@app/views/' . $controller . '/' . $view) . '.php', $text);

        }

        if ($this->modelName) {

            $model = Yii::$app->view->render('@worstinme/zoo/applications/default/models/Items', [
                'modelName' => $this->modelName,
                'tableName' => $this->model_table_name,
            ]);

            file_put_contents(Yii::getAlias('@app/models/' . $this->modelName . '.php'), $model);

            $searchModel = Yii::$app->view->render('@worstinme/zoo/applications/default/models/S', [
                'modelName' => $this->modelName,
                'tableName' => $this->model_table_name,
            ]);

            file_put_contents(Yii::getAlias('@app/models/Search' . $this->modelName . '.php'), $searchModel);

        }

        return true;
    }

    public function tableExists($attribute, $params)
    {
        $tableSchema = Yii::$app->db->schema->getTableSchema($this->model_table_name);

        if ($tableSchema !== null) {
            $this->addError($attribute, 'Table already exists');
        }

    }

    public function getElements()
    {
        return $this->hasMany(Elements::className(), ['app_id' => 'id'])->indexBy('name');
    }

    public function modelFileExists($attribute, $params)
    {
        if (is_file(Yii::getAlias('@app/models/') . $this->modelName . '.php')) {
            $this->addError($attribute, 'Model file already exists');
        }

    }

    public function exampleExists($attribute, $params)
    {
        if ($this->example === null) {
            $this->example = 'default';
        }

        if (!is_dir(Yii::getAlias('@worstinme/zoo/applications/') . $this->example)) {
            $this->addError($attribute, 'Application example is not exists');
        }

    }

    public function getExamples()
    {

        $dirs = \worstinme\zoo\helpers\AppHelper::findDirectories(Yii::getAlias('@worstinme/zoo/applications'));

        $list = [];

        if (count($dirs)) {
            foreach ($dirs as $dir) {
                $list[$dir] = $dir;
            }
        }

        return $list;
    }

    public function getControllerName()
    {
        return Inflector::camelize($this->name) . 'Controller';
    }

    public function getModelName()
    {
        return Inflector::camelize($this->model_table_name);
    }

    public function controllerFileExists($attribute, $params)
    {
        if (is_file(Yii::getAlias('@app/controllers/') . $this->controllerName . '.php')) {
            $this->addError($attribute, 'Controller file already exists');
        }

    }

    public function viewFolderExists($attribute, $params)
    {
        if (is_dir(Yii::getAlias('@app/views/') . strtolower($this->name))) {
            $this->addError($attribute, 'View folder already exists');
        }

    }


    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if ($insert) {
                $this->templates = Json::encode(require(Yii::getAlias('@worstinme/zoo/applications/default/Templates.php')));
            }

            return true;
        }

        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {

        if ($insert) {

            $this->create();

            $elements = require(Yii::getAlias('@worstinme/zoo/applications/default/Elements.php'));

            if (is_array($elements) && count($elements)) {
                foreach ($elements as $key => $params) {

                    $element = new Elements;

                    $element->setAttributes($params);
                    $element->name = $key;
                    $element->app_id = $this->id;
                    $element->all_categories = 1;

                    if (!$element->save()) {
                        print_r($element->errors);
                    }

                }
            }

        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $db = Yii::$app->db;

        foreach ($this->elements as $element) $element->delete();
        foreach ($this->categories as $element) $element->delete();
        foreach ($this->items as $element) $element->delete();

        unlink(Yii::getAlias('@app/controllers/' . $this->controllerName . '.php'));

        if ($this->modelName !== null) {
            if (is_file(Yii::getAlias('@app/models/' . $this->modelName . '.php'))) {
                unlink(Yii::getAlias('@app/models/' . $this->modelName . '.php'));
            }
            if (is_file(Yii::getAlias('@app/models/Search' . $this->modelName . '.php'))) {
                unlink(Yii::getAlias('@app/models/Search' . $this->modelName . '.php'));
            }
        }

        $controller = strtolower($this->name);

        $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@app/views/' . $controller));

        if (!empty($controller) && count($files)) {
            foreach ($files as $file) {
                unlink($file);
            }
        }

        rmdir(Yii::getAlias('@app/views/' . $controller));

        parent::afterDelete();

    }

    public function getParentCategories()
    {
        return $this->hasMany(Categories::className(), ['app_id' => 'id'])->where(['parent_id' => 0])->orderBy('sort ASC');
    }

    public function getCategories()
    {
        $categories = $this->hasMany(Categories::className(), ['app_id' => 'id']);
        if (count(Yii::$app->zoo->languages) > 1)
            $categories->andWhere(['lang' => Yii::$app->zoo->lang]);
        return $categories->orderBy('sort ASC')->inverseOf('app');
    }

}