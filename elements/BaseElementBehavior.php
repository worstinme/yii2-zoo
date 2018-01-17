<?php

namespace worstinme\zoo\elements;

use worstinme\zoo\backend\models\Items;
use worstinme\zoo\backend\models\ItemsElements;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * @property Items $owner
 */
class BaseElementBehavior extends \yii\base\Behavior
{
    public $attribute;

    public $multiple = false;

    public $field = 'value_string';
    public $json_field = false;

    protected $value;
    public $old_value;

    protected $_elements;

    public function init()
    {
        if ($this->attribute === null) {
            throw new InvalidConfigException('Attribute is required for element behavior ' . self::className());
        }
        parent::init();
    }

    public function __get($name)
    {
        if ($name == $this->attribute) {
            return $this->getValue();
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($name == $this->attribute) {
            $this->setValue($value);
        } else {
            parent::__set($name, $value);
        }
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $name == $this->attribute || $checkVars && property_exists($this, $name);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $name == $this->attribute || $checkVars && property_exists($this, $name);
    }

    public function getIsRendered()
    {
        return true;
    }

    public function getOwnerAttribute()
    {
        return str_replace("element_", "", $this->attribute);
    }

    public function getValue()
    {
        // TODO: сделать валидацию JSON строки

        if ($this->owner->hasAttribute($this->ownerAttribute)) {
            return $this->json_field ? Json::decode($this->owner->getAttribute($this->ownerAttribute)) : $this->owner->getAttribute($this->ownerAttribute);
        }

        if ($this->value === null) {

            $values = ArrayHelper::getColumn($this->elements, $this->field);

            if ($this->multiple) {

                $this->value = $values;

            } else {

                $this->value = isset($values[0]) ? $values[0] : null;

            }

            $this->old_value = $this->value;

        }

        return $this->json_field ? array_map(function ($e) {
            return $e ? Json::decode($e) : (array)$e;
        }, $this->value) : $this->value;
    }

    public function setValue($value)
    {
        if ($this->json_field) {
            if ($this->multiple) {
                $value = is_array($value) ? array_map(function ($e) {
                    return $e ? Json::encode($e) : $e;
                }, $value) : [];
            } else {
                $value = Json::encode((array)$value);
            }
        }

        if ($this->owner->hasAttribute($this->ownerAttribute)) {
            $this->owner->setAttribute($this->ownerAttribute, $value);
        } elseif (is_array($value)) {
            $this->value = array_values($value);
        } else {
            $this->value = $value;
        }
    }


    public function rules()
    {
        return [];
    }

    public function rulesRequired()
    {
        if ($this->element->required) {
            if ($this->element->all_categories) {
                return [
                    [$this->attribute, 'required'],
                ];

            } elseif (count($categories = $this->element->categories)) {

                $categories = Json::encode($categories);
                $input_id = Html::getInputId($this->owner, 'element_category');
                $whenClient = "function(attribute, value){ 
    var arr=$categories; 
    var categories = $('#$input_id').val();
    if  (categories instanceof Array) {
        for (var i in categories) {
            if  (arr.indexOf(categories[i]) >= 0) {
                return true;
            }
        }
    }
    return false;  
}";

                return [
                    [$this->attribute, 'required', 'when' => function ($model) {
                      //  Yii::warning('Атрибут обязателен '.($this->element->isActiveForModel($model)?'1':'0'));
                        return $this->element->isActiveForModel($model);
                    }, 'whenClient' => $whenClient]
                ];
            }

        }

        return [];
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterSave()
    {
        if (!$this->owner->hasAttribute($this->ownerAttribute)) {

            $this->getElements();

            $values = (array)$this->getValue();

            if (count($values)) {

                foreach ($values as $key => $value) {

                    if ($value !== null && $value !== '') {

                        if ($this->json_field) {
                            $value =  Json::encode((array)$value);
                        }

                        if (isset($this->_elements[$key])) {

                            //обновим данные имеющихся элементов
                            $this->_elements[$key]->{$this->field} = $value;
                            $this->_elements[$key]->save();


                        } else {
                            //или создадим новые элементы
                            $element = new ItemsElements([
                                'item_id' => $this->owner->id,
                                'element' => $this->element->name,
                            ]);

                            $element->{$this->field} = $value;
                            $element->save();

                            $this->_elements[] = $element;
                        }

                    } elseif (isset($this->_elements[$key])) {
                        // удалим пустой элемент если он есть
                        $this->_elements[$key]->delete();
                        unset($this->_elements[$key]);
                    }
                }

                if ($key < count($this->_elements)) {
                    //удаление лишних элементов
                    $key++;
                    while (isset($this->_elements[$key])) {
                        $this->_elements[$key]->delete();
                        unset($this->_elements[$key]);
                        $key++;
                    }
                }

            } else if (count($this->_elements)) {
                //удаление лишних элементов
                foreach ($this->_elements as $key => $element) {
                    $element->delete();
                    unset($this->_elements[$key]);
                }
            }

        }

    }

    public function afterDelete()
    {
        if (!$this->owner->hasAttribute($this->ownerAttribute)) {

            //удаление всех элементов
            if (count($this->elements)) {
                foreach ($this->_elements as $key => $element) {
                    $element->delete();
                    unset($this->_elements[$key]);
                }
            }

        }
    }

    protected function getElement()
    {
        // доступ к конфигу элемента
        return $this->owner->app->getElement($this->attribute);
    }

    protected function getElements()
    {
        if ($this->_elements === null) {
            $attribute = $this->ownerAttribute;
            $this->_elements = array_values(array_filter($this->owner->itemsElements, function ($element) use ($attribute) {
                return $element->element == $attribute;
            }));
        }
        return $this->_elements;
    }


}