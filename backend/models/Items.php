<?php

namespace worstinme\zoo\backend\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class Items extends \yii\db\ActiveRecord
{

    private $renderedElements = [];
    public $values = [];
    public $elements_ = [];

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    public static function find()
    {
        return new ItemsQuery(get_called_class());
    }

    public function afterFind() {
        $this->regBehaviors();
        return parent::afterFind();
    }

    public function getElements() {
        return $this->hasMany(Elements::className(),['app_id'=>'app_id'])->indexBy('name');
    }

    public function getItemsElements() {
        return $this->hasMany(ItemsElements::className(),['item_id'=>'id']);
    }

    public function getApp() {
        return $this->hasOne(Applications::className(),['id'=>'app_id']);
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(),['id'=>'category_id'])
            ->viaTable('{{%zoo_items_categories}}',['item_id'=>'id']);
    }

    public function regBehaviors() {
        
        $this->elements_ = array_unique(ArrayHelper::getColumn($this->elements,'type'));
               
        foreach ($this->elements_ as $behavior) {
            if (is_file(Yii::getAlias('@worstinme/zoo/elements/'.$behavior.'/Element.php'))) {
                $behavior_class = '\worstinme\zoo\elements\\'.$behavior.'\Element';
                $this->attachBehavior($behavior,$behavior_class::className());
            }
        }

        return true;
    }

    public function __get($name)
    { 
        if (!in_array($name, $this->attributes())
                && $name != 'elements'
                && isset($this->elements[$name])
                && $this->elements[$name] !== null 
                && ($behavior = $this->getBehavior($this->elements[$name]->type)) !== null) {
            return $behavior->getValue($name);
        } else {
            return parent::__get($name);
        }
    }  
    
    public function __set($name, $value)
    {
        if (!in_array($name, $this->attributes())
                && $name != 'elements'
                && isset($this->elements[$name])
                && $this->elements[$name] !== null 
                && ($behavior = $this->getBehavior($this->elements[$name]->type)) !== null) {
            return $behavior->setValue($name,$value);
        } else {
            parent::__set($name, $value);
        }
    } 

/*
    DEPRECATED

    public function getElementValue($name) 
    {   
        
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }

        $values = [];

        foreach ($this->itemsElements as $element) 
            if ($element->element == $name) 
                $values[] = $element->value_text;

        return count($values) && $this->elements[$name]->multiple === false ? $values[0] : $values;
    }

    public function setElementValue($name,$value) 
    {   
        return $this->values[$name] = $value;
    }  */

    /**
     * @inheritdoc
     */
    public function rules() 
    {
        $rules  = [
           // ['user_id','required'],
            [['metaDescription','metaKeywords'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],
            [['template'], 'safe'],
        ];

        foreach ($this->elements_ as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules,$behavior_rules);
                }
            }
        }

        return $rules;
    }

    public function attributeLabels()
    {
        $labels = [
            'categories' => Yii::t('backend', 'Категории'),
        ];

        foreach ($this->elements as $key => $element) {
            $labels[$key] = $element->title;
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
        if (isset($this->elements[$element]) && is_array($this->elements[$element]) && array_key_exists($param, $this->elements[$element])) {
            return $this->elements[$element][$param];
        }

        if (is_array($this->elements[$element]) && array_key_exists('params', $this->elements[$element])) {
            
            $params = \yii\helpers\Json::decode($this->elements[$element]['params']);
        
            if (is_array($params) && array_key_exists($param, $params)) {
                return $params[$param];
            }

        }

        return $default;
    }

    //metaTitle
    public function getMetaTitle() {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        return isset($params['metaTitle']) ? $params['metaTitle'] : '';
    }
    public function setMetaTitle($s) {
        $params = $this->params !== null ? Json::decode($this->params) : null; $params['metaTitle'] = $s;
        return $this->params = Json::encode($params);
    }

    //metaKeywords
    public function getMetaKeywords() {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        return isset($params['metaKeywords']) ? $params['metaKeywords'] : '';
    }
    public function setMetaKeywords($s) {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        $params['metaKeywords'] = $s;
        return $this->params = Json::encode($params);
    }

    //metaDescription
    public function getMetaDescription() {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        return isset($params['metaDescription']) ? $params['metaDescription'] : '';
    }
    public function setMetaDescription($s) {
        $params = Json::decode($this->params); $params['metaDescription'] = $s;
        return $this->params = Json::encode($params);
    }

    public function getTemplate($name = 'full') {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        return isset($params['templates']) && isset($params['templates'][$name]) ? $params['templates'][$name] : [];
    }

    public function getRenderedElements() 
    {

        if (!count($this->renderedElements)) {
            
            $renderedElements = [];

            $elementByCategories = (new \yii\db\Query())->select('element_id')->from('{{%zoo_elements_categories}}')
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
            'container' => ".element-".$attribute,
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


    public function afterSave($insert, $changedAttributes)
    {

        foreach ($this->values as $attribute => $value) {

            $elements = [];
            $attributes = [];

            if (!in_array($attribute, $this->attributes()) && $attribute != 'category') {

                $attributes[] = $attribute;
                

                if ($this->elements[$attribute]->multiple) {
                    foreach ($value as $v) {
                        $elements[] = [
                            $this->id, 
                            $attribute,
                            $v['value_text'],
                            $v['value_int'],
                            $v['value_string'],
                            $v['value_float'],
                        ];
                    }
                }
                else {
                    $elements[] = [
                        $this->id, 
                        $attribute,
                        $value['value_text'],
                        $value['value_int'],
                        $value['value_string'],
                        $value['value_float'],
                    ];
                }
            }

            if (count($elements)) {
                
                Yii::$app->db->createCommand()->delete('{{%zoo_items_elements}}',['item_id'=>$this->id,'element'=>
                            $attributes])->execute();

                Yii::$app->db->createCommand()->batchInsert('{{%zoo_items_elements}}', 
                            ['item_id', 'element','value_text','value_int','value_string','value_float'], 
                                $elements)->execute();
            }

        }

        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%zoo_items_elements}}', ['item_id'=>$this->id])->execute();

        parent::afterDelete();
        
    }
}
