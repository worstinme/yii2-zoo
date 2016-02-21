<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
//TODO:  
// 1    активные атрибуты
// 2    новые активные атрибуты
// 3    снятые атрибуты
// 4    валидаторы

class Item extends \yii\db\ActiveRecord
{

    public $elements = [];
    private $renderedElements = [];
    private $newItemsFields = [];

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    {
        
        $rules  = [
           // [['user_id'], 'required'],
        ];

        $behaviors = array_unique(ArrayHelper::getColumn($this->elements,'type'));

        foreach ($behaviors as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
                
            }
        }

        return $rules;
    }

    public function afterFind() {
        $this->registerElements();
        $this->attachBehaviors();
        return parent::afterFind();
    }

    public function getItemsFields() {
        return $this->hasMany(ItemsFields::className(),['item_id'=>'id'])->indexBy('element');
    }

    public function registerElements() {
        $this->elements = (new \yii\db\Query())->select('*')->from('{{%zoo_fields}}')
                ->where(['app_id'=>$this->app_id])
                ->indexBy('name')
                ->all();
        return true;
    }

    public function attachBehaviors($behaviors = null) {

        if ($behaviors === null) {
            $behaviors = array_unique(ArrayHelper::getColumn($this->elements,'type'));
        }        

        foreach ($behaviors as $behavior) {
            if (is_file(Yii::getAlias('@worstinme/zoo/fields/'.$behavior.'/ElementBehavior.php'))) {
                $behavior_class = '\worstinme\zoo\fields\\'.$behavior.'\ElementBehavior';
                $this->attachBehavior($behavior,$behavior_class::className());
            }
        }

        return true;
    }

    public function __get($name)
    {
        if (isset($this->elements[$name])) {
            return $this->getElementValue($name);
        } else {
            return parent::__get($name);
        }
    }
    
    public function __set($name, $value)
    {
        if (isset($this->elements[$name])) {
            $this->setElementValue($name, $value);
        } else {
            parent::__set($name, $value);
        }
    } 

    public function getElementValue($name) 
    {
        if ($this->itemsFields[$name] !== null) {
            return $this->itemsFields[$name]->value_text;
        }
        elseif (is_array($this->elements[$name]) && array_key_exists('value', $this->elements[$name])) {
            return $this->elements[$name]['value'];
        }
        return null;
    }

    public function setElementValue($name,$value) 
    {
       // if (is_array($this->elements[$name]) //&& array_key_exists('value', $this->elements[$name])) {
        if (isset($this->elements[$name])) {
            $elements = $this->elements;
            $elements[$name]['value'] = $value;
            return $this->elements = $elements;
        }
        return false;
    } 

    public function attributeLabels()
    {
        $labels = [
            'categories' => Yii::t('admin', 'Категории'),
        ];

        foreach ($this->elements as $key => $element) {
            $labels[$key] = $element['title'];
        }

        return $labels;
    }

    public function getElementsByType($type) 
    {
        $elements = [];
        foreach ($this->elements as $key => $element) {
            if ($element['type'] == $type) {
                $elements[] = $key;
            }
        }
        return $elements;
    }

    public function getElementParam($element,$param,$default = null)
    {
        if (is_array($this->elements[$name]) && array_key_exists($param, $this->elements[$name])) {
            return $this->elements[$name][$param];
        }
        return $default;
    }

    public function getRenderedElements() 
    {

        if (!count($this->renderedElements)) {
            
            $renderedElements = [];

            $elementByCategories = (new \yii\db\Query())->select('field_id')->from('{{%zoo_fields_categories}}')
                    ->where(['category_id'=>$this->category])->orWhere(['category_id'=>0])->column();

            foreach ($this->elements as $key => $element) {
                if (in_array($element['id'], $elementByCategories)) {
                    if (($behavior = $this->getBehavior($element['type'])) !== null) {
                        if ($behavior->isRendered($element['name'])) {
                            $renderedElements[] = $key;
                        }
                    }  
                    else {
                        $renderedElements[] = $key;
                    }
                }
            }

            $this->renderedElements = $renderedElements;
        }

        return $this->renderedElements;
    }

    public function addValidators($view,$attribute) { // js to form

        $inputID = Html::getInputId($this, $attribute);

        $validators = [];

        foreach ($this->getActiveValidators($attribute) as $validator) {
            $js = $validator->clientValidateAttribute($this, $attribute, $view); 
            if ($js != '') {
                if ($validator->whenClient !== null) {
                    $js = "if (({$validator->whenClient})(attribute, value)) { $js }";
                }
                $validators[] = $js;
            }   
        }

        $options = Json::htmlEncode([
            'id' => $inputID,
            'name' => $attribute,
            'container' => ".field-".$attribute,
            'input' => "#$inputID",
            'validate' => new \yii\web\JsExpression("function (attribute, value, messages, deferred, \$form) {" . implode('', $validators) . '}'),
            'validateOnChange' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validationDelay' => 500,
            'encodeError' => true,
            'error' => '.uk-form-help-block.uk-text-danger',
        ]);

        return "$('#form').yiiActiveForm('add', $options);";
    }


    public function getCategories() {
        return $this->hasMany(Categories::className(),['id'=>'category_id'])
            ->viaTable('{{%zoo_items_categories}}',['item_id'=>'id'])->indexBy('{{%zoo_categories}}.id');
    }

    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->elements as $attribute => $element) {
            if (($item = ItemsFields::find()->where(['element'=>$element])->one()) === null) {
                $item = new ItemsFields;
                $item->element = $attribute;
                $item->item_id = $this->id;
            }
            $item->value_text = $element['value'];
            $item->save();
        }

        print_r($this->elements);
        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        parent::afterDelete();
        
    }
}
