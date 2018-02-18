<?php

namespace worstinme\zoo\backend\models;

use worstinme\zoo\models\Items;
use Yii;
use yii\db\Query;
use yii\helpers\Json;

class Categories extends \worstinme\zoo\models\Categories
{
    public $alternateIds;

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            [
                'class' => \worstinme\zoo\behaviors\ImageSrcHostBehavior::className(),
                'attributes' => ['image', 'preview', 'icon'],
            ],
            [
                'class' => \worstinme\zoo\behaviors\SluggableBehavior::className(),
                'uniqueValidator' => ['attributes' => 'alias', 'targetAttribute' => ['alias', 'lang']],
            ],
        ];
    }

    public function rules()
    {
        $rules = [
            [['name', 'alias', 'app_id'], 'required'],
            ['alias', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['app_id', 'match', 'pattern' => '#^[\w_-]+$#i'],
            [['parent_id', 'sort', 'state', 'flag', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'safe'],
            [['name', 'alias', 'image', 'preview', 'subtitle'], 'string', 'max' => 255],
            [['meta_description', 'meta_keywords', 'content', 'intro', 'quote'], 'string'],
            [['meta_title'], 'string', 'max' => 255],
            ['lang', 'string', 'max' => 255, 'skipOnEmpty' => true],
            ['alternateIds', 'each', 'rule' => ['integer']],
            //defaults
            ['state', 'default', 'value' => 1],
            ['parent_id', 'default', 'value' => 0],

        ];

        if (count(Yii::$app->zoo->languages)) {
            $rules[] = ['lang', 'required'];
        }

        return $rules;
    }

    public function afterFind()
    {
        $this->alternateIds = (new Query())->select('alternate_id')->from('{{%categories_alternates}}')->where(['category_id' => $this->id])->column();
        return parent::afterFind();
    }

    public static function buildTree($lang, $categories = null, $array = [], $prefix = null, $parent = 0)
    {

        if ($categories === null) {
            $categories = self::find()->select(['id', 'name', 'parent_id'])->where(['lang' => $lang])->indexBy('id')->asArray()->all();
        }

        if (count($categories)) {
            foreach ($categories as $key => $category) {
                if ($category['parent_id'] == $parent) {
                    $array[$category['id']] = ($prefix === null ? '' : $prefix . ' ') . $category['name'];
                    unset($categories[$key]);
                    $array = self::buildTree($lang, $categories, $array, $prefix . '-', $category['id']);
                }
            }
        }

        return $array;

    }

    public function afterDelete()
    {
        $db = Yii::$app->db;

        $db->createCommand()->delete('{{%items_categories}}', ['category_id' => $this->id])->execute();
        $db->createCommand()->delete('{{%elements_categories}}', ['category_id' => $this->id])->execute();
        $db->createCommand()->update('{{%categories}}', ['parent_id' => $this->parent_id], ['parent_id' => $this->id])->execute();

        parent::afterDelete();

    }

}
