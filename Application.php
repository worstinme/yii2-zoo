<?php

namespace worstinme\zoo;

use worstinme\zoo\backend\models\Categories;
use worstinme\zoo\elements\BaseElement;
use worstinme\zoo\models\ApplicationsContent;
use worstinme\zoo\models\Items;
use Yii;
use worstinme\zoo\backend\models\Elements;

class Application extends \yii\base\Component
{
    /** @var string Yii2 application ID */
    public $app_id;

    public $baseUrl;
    public $basePath;

    /** @var string uniquie application id */
    public $id;

    /** @var string application Title */
    public $title;
    public $description = 'worstinme\zoo\Application';

    /** @var array url component config merged with ZOO component config */
    public $urlRuleComponent = [

    ];

    private $_elements;
    private $_system;

    public $lang;

    public function init()
    {
        parent::init();

    }

    public function getSystemElements()
    {
        if ($this->_system === null) {

            $this->_system = [];

            $elements = [
                'name' => '\worstinme\zoo\elements\system\Element',
                'lang' => '\worstinme\zoo\elements\system\lang\Element',
                'category' => '\worstinme\zoo\elements\system\category\Element',
                'alias' => '\worstinme\zoo\elements\system\Element',
                'meta_title' => '\worstinme\zoo\elements\system\Element',
                'meta_description' => '\worstinme\zoo\elements\system\Element',
                'meta_keywords' => '\worstinme\zoo\elements\system\Element',
                'alternate' => '\worstinme\zoo\elements\system\alternate\Element',
            ];

            foreach ($elements as $key => $element) {
                $this->_system['element_' . $key] = Yii::createObject([
                    'class' => $element,
                    'name' => 'element_' . $key,
                    'app_id' => $this->id,
                ]);
            }
        }

        return $this->_system;
    }

    public function getElements()
    {
        if ($this->_elements === null) {
            $this->_elements = BaseElement::find()->where(['app_id' => $this->id])->indexBy(function ($f) {
                return 'element_' . $f->name;
            })->orderBy('sort')->all();
        }
        return $this->_elements;
    }

    public function getElement($name, $exception = true)
    {
        if (isset($this->systemElements[$name])) {
            return $this->_system[$name];
        }

        if (isset($this->elements[$name])) {
            return $this->_elements[$name];
        }

        if ($exception) {
            throw new \BadFunctionCallException("Element \"$name\" is not exists");
        }

        return null;
    }

    public function getCategories()
    {
        return Categories::find()->where(['app_id' => $this->id]);
    }

    public function getContent($lang = null)
    {
        return ApplicationsContent::findOne(['app_id' => $this->id, 'lang' => $lang === null ? $this->lang : $lang]);
    }

    public function getUrl($lang = null)
    {
        return ['/' . $this->id . '/index', 'lang' => $lang === null ? $this->lang : $lang];
    }
    /*
    перетащил из предыдущей модели
    public function getSchedules() {
        return (new Query())->select(['id','mo','tu','we','th','fr','sa','su','start_at','finish_at'])
            ->from('{{%schedule}}')->indexBy('id')->all();
    } */

}