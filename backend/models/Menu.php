<?php

namespace worstinme\zoo\backend\models;

use Yii;
use worstinme\zoo\models\Items;
use worstinme\zoo\models\Applications;
use worstinme\zoo\models\Categories;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Menu extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    public static function get($alias, $items = [])
    {

        $zoo_items = [];

        return $items;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'menu', 'lang'], 'required'],
            [['category'], 'required', 'when' => function ($model) {
                return in_array($model->type, [2]);
            }],
            [['application'], 'required', 'when' => function ($model) {
                return in_array($model->type, [1, 2, 3]);
            }],
            [['item'], 'required', 'when' => function ($model) {
                return in_array($model->type, [3]);
            }],
            [['url'], 'required', 'when' => function ($model) {
                return in_array($model->type, [4, 5]);
            }],
            [['category', 'item', 'parent_id', 'sort', 'type'], 'integer'],
            [['link', 'content'], 'string'],
            [['name', 'menu', 'application'], 'string', 'max' => 255],
            ['menu', 'match', 'pattern' => '#^[\w_-]+$#i'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    public function getTypes()
    {

        return [
            1 => Yii::t('zoo', 'MENU_TYPE_APPLICATION'),
            2 => Yii::t('zoo', 'MENU_TYPE_CATEGORY'),
            3 => Yii::t('zoo', 'MENU_TYPE_ITEM'),
            4 => Yii::t('zoo', 'MENU_TYPE_JSON'),
            5 => Yii::t('zoo', 'MENU_TYPE_LINK'),
            6 => Yii::t('zoo', 'MENU_TYPE_WIDGET'),
        ];

    }

    public function getApplication()
    {
        $params = $this->params ? Json::decode($this->params) : [];
        return isset($params['application']) ? $params['application'] : null;
    }

    public function setApplication($value)
    {
        $params = $this->params ? Json::decode($this->params) : [];
        $params['application'] = $value;
        return $this->params = Json::encode($params);
    }

    public function getCategory()
    {
        $params = $this->params ? Json::decode($this->params) : [];
        return isset($params['category']) ? $params['category'] : null;
    }

    public function setCategory($value)
    {
        $params = $this->params ? Json::decode($this->params) : [];
        $params['category'] = $value;
        return $this->params = Json::encode($params);
    }

    public function getItem()
    {
        $params = $this->params ? Json::decode($this->params) : [];
        return isset($params['item']) ? $params['item'] : null;
    }

    public function setItem($value)
    {
        $params = $this->params ? Json::decode($this->params) : [];
        $params['item'] = $value;
        return $this->params = Json::encode($params);
    }

    public function getLink()
    {
        $params = $this->params ? Json::decode($this->params) : [];
        return isset($params['link']) ? $params['link'] : null;
    }

    public function setLink($value)
    {
        $params = $this->params ? Json::decode($this->params) : [];
        $params['link'] = $value;
        return $this->params = Json::encode($params);
    }

    public function getContent()
    {
        $params = $this->params ? Json::decode($this->params) : [];
        return isset($params['content']) ? $params['content'] : null;
    }

    public function setContent($value)
    {
        $params = $this->params ? Json::decode($this->params) : [];
        $params['content'] = $value;
        return $this->params = Json::encode($params);
    }

    public function getRelated()
    {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id'])->orderBy('sort ASC');
    }

    public function getParents()
    {
        if ($this->isNewRecord) {
            return self::find()->select(['name'])->where(['menu' => $this->menu])->indexBy('id')->column();
        } else {
            return self::find()->select(['name'])->where(['<>', 'id', $this->id])->andWhere(['menu' => $this->menu])->indexBy('id')->column();
        }
    }

    public function getUrl()
    {
        switch ($this->type) {
            case 1:
                return Yii::$app->zoo->getApplication($this->application)->getUrl($this->lang);
            case 2:
                if (($item = Items::find()->where([Items::tablename() . '.id' => $this->item_id])->one()) !== null) {
                    return $item->url;
                }
                break;
            case 3:
                if (($category = Categories::findOne($this->category)) !== null) {
                    return $category->url;
                }
                break;
            case 4:
                return \yii\helpers\Json::decode($this->content);
            case 5:
                return $this->link;
            case 6:
                return $this->content;

        }
        return '#';
    }

}
