<?php

namespace worstinme\zoo\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

class Items extends \yii\db\ActiveRecord
{

    const SCENARIO_EDIT = 'edit';
    const SCENARIO_SUBMISSION = 'submission';

    const STATE_ACTIVE = 1;
    const STATE_HIDDEN = 0;

    private $renderedElements = [];
    private $elements_types;

    public $values = [];
    public $backend = false;
    public $_oldValues = [];

    public function __get($name)
    {
        if (!in_array($name, $this->attributes())
            && !empty($this->app->elements[$name])
            && ($behavior = $this->getBehavior($this->app->elements[$name]->type)) !== null
        ) {
            return $behavior->getValue($name);
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if (!in_array($name, $this->attributes())
            && !empty($this->app->elements[$name])
            && ($behavior = $this->getBehavior($this->app->elements[$name]->type)) !== null
        ) {
            return $behavior->setValue($name, $value);
        } else {
            return parent::__set($name, $value);
        }
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function tableName()
    {
        return '{{%zoo_items}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_EDIT] = ['username', 'password'];
        $scenarios[self::SCENARIO_SUBMISSION] = ['username', 'email', 'password'];

        return $scenarios;
    }

    public function afterFind()
    {
        $this->regBehaviors();

        return parent::afterFind();
    }

    public function getApp()
    {
        return $this->hasOne(Applications::className(), ['id' => 'app_id'])->with('elements')->inverseOf('items');
    }

    public function getItemsElements()
    {
        return $this->hasMany(ItemsElements::className(), ['item_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])
            ->viaTable('{{%zoo_items_categories}}', ['item_id' => 'id']);
    }

    public function getElements()
    {
        return $this->app !== null && $this->app->elements !== null ? $this->app->elements : [];
    }

    public function getElementsTypes()
    {

        if ($this->elements_types === null) {
            $this->elements_types = array_unique(ArrayHelper::getColumn($this->app->elements, 'type'));
        }

        return $this->elements_types;
    }

    public function getParentCategory()
    {
        if (count($this->categories)) {
            foreach ($this->categories as $category) {
                if ($category->parent_id == 0) {
                    return $category;
                }
            }

            return $this->categories[0];
        }

        return null;
    }

    public function regBehaviors()
    {

        foreach ($this->elementsTypes as $behavior) {
            if (is_file(Yii::getAlias('@worstinme/zoo/elements/' . $behavior . '/Element.php'))) {
                $behavior_class = '\worstinme\zoo\elements\\' . $behavior . '\Element';
                $this->attachBehavior($behavior, $behavior_class::className());
            }
        }

    }


    public function rules()
    {
        $rules = [
            // ['user_id','required'],
            [['metaDescription', 'metaKeywords'], 'string'],
            [['metaTitle'], 'string', 'max' => 255],
            ['app_id', 'integer'],
            [['flag', 'state',], 'integer'],
        ];

        foreach ($this->elementsTypes as $behavior_name) {
            if (($behavior = $this->getBehavior($behavior_name)) !== null) {
                $behavior_rules = $behavior->rules($this->getElementsByType($behavior_name));
                if (count($behavior_rules)) {
                    $rules = array_merge($rules, $behavior_rules);
                }
            }
        }

        foreach ($this->getRenderedElements() as $attribute) {
            if ($this->elements[$attribute]->required) {
                $rules[] = [$attribute, 'required'];
            }
        }

        return $rules;
    }

    public function attributeLabels()
    {

        $labels = [];

        foreach ($this->elements as $key => $element) {
            $labels[$key] = $element->label;
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

    public function setOldValue($name,$value) {
        return $this->_oldValues[$name] = $value;
    }

    public function getOldValue($name) {
        return $this->_oldValues[$name]??null;
    }

    /*  public function getElementParam($element,$param,$default = null)
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
      } */

    //metaTitle
    public function getMetaTitle()
    {
        $params = $this->params !== null ? Json::decode($this->params) : null;

        return !empty($params['metaTitle']) ? $params['metaTitle'] : $this->name;
    }

    public function setMetaTitle($s)
    {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        $params['metaTitle'] = $s;

        return $this->params = Json::encode($params);
    }

    //metaKeywords
    public function getMetaKeywords()
    {
        $params = $this->params !== null ? Json::decode($this->params) : null;

        return !empty($params['metaKeywords']) ? $params['metaKeywords'] : '';
    }

    public function setMetaKeywords($s)
    {
        $params = $this->params !== null ? Json::decode($this->params) : null;
        $params['metaKeywords'] = $s;

        return $this->params = Json::encode($params);
    }

    //metaDescription
    public function getMetaDescription()
    {
        $params = $this->params !== null ? Json::decode($this->params) : null;

        return !empty($params['metaDescription']) ? $params['metaDescription'] : '';
    }

    public function setMetaDescription($s)
    {
        $params = Json::decode($this->params);
        $params['metaDescription'] = $s;

        return $this->params = Json::encode($params);
    }

    public function getRenderedElements()
    {
        $this->renderedElements = [];

        $elementByCategories = (new \yii\db\Query())->select('element_id')->from('{{%zoo_elements_categories}}')
            ->where(['category_id' => $this->category])->orWhere(['category_id' => 0])->column();

        foreach ($this->elements as $key => $element) {
            if (in_array($element->id, $elementByCategories)) {
                if (($behavior = $this->getBehavior($element->type)) !== null) {
                    if ($behavior->isRendered($element['name'])) {
                        $this->renderedElements[] = $key;
                    }
                } else {
                    $this->renderedElements[] = $key;
                }
            }
        }

        return $this->renderedElements;
    }

    public function addValidators($view, $attribute)
    { // js to form

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
            'container' => ".element-" . $attribute,
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


    public function getUrl()
    {

        if ($this->app->simpleItemLinks) {
            $url = ['/' . $this->app->name . '/a', 'a' => $this->id];
        } elseif ($this->parentCategory !== null) {
            $url = ['/' . $this->app->name . '/ab', 'a' => $this->parentCategory->alias, 'b' => !empty($this->alias) ? $this->alias : $this->id];
        } else {
            $url = ['/' . $this->app->name . '/a', 'a' => !empty($this->alias) ? $this->alias : $this->id];
        }

        if ($this->lang !== null) {
            $url['lang'] = $this->lang;
        }

        return $url;
    }

    public function getBreadcrumbs($selfUrl = false)
    {
        $crumbs[] = ['label' => $this->app->title, 'url' => $this->app->url];
        if (is_array($this->categories)) {
            foreach ($this->categories as $category) {
                $crumbs[] = ['label' => $category->name, 'url' => $category->url];
            }
        }
        $crumbs[] = $selfUrl ? ['label' => $this->name, 'url' => $this->url] : $this->name;

        return $crumbs;
    }

    public function getTemplate($name)
    {
        $template = $this->app->template;

        return !empty($template[$name]) ? $template[$name] : [];
    }

    public function getTemplateRows($name)
    {
        $template = $this->getTemplate($name);

        return !empty($template['rows']) ? $template['rows'] : [['items' => []]];
    }

    public function getRendererView($name)
    {

        $template = $this->getTemplate($name);

        if (!empty($template['rendererViewPath']) && is_file(Yii::getAlias($template['rendererViewPath']))) {
            return $template['rendererViewPath'];
        }

        return '@worstinme/zoo/renderers/uikit_grid/view';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {


            if ($insert) {
                $this->user_id = Yii::$app->user->identity->id;
            }

            return true;
        } else return false;
    }

    public function load($data, $formName = null)
    {
        return parent::load($data, $formName);
    }

    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

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
                } else {
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

                Yii::$app->db->createCommand()->delete('{{%zoo_items_elements}}', ['item_id' => $this->id, 'element' =>
                    $attributes])->execute();

                Yii::$app->db->createCommand()->batchInsert('{{%zoo_items_elements}}',
                    ['item_id', 'element', 'value_text', 'value_int', 'value_string', 'value_float'],
                    $elements)->execute();
            }

        }

    }

    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%zoo_items_elements}}', ['item_id' => $this->id])->execute();
        Yii::$app->db->createCommand()->delete('{{%zoo_items_categories}}', ['item_id' => $this->id])->execute();
        parent::afterDelete();

    }


}
