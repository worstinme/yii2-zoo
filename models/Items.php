<?php
namespace worstinme\zoo\models;

use Yii;

class Items extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%items}}';
    }

    public function afterFind()
    {
        $this->regBehaviors();
        return parent::afterFind();
    }

    public function getApp()
    {
        return Yii::$app->zoo->getApplication($this->app_id, false);
    }

    public function regBehaviors()
    {
        foreach ($this->app->elements as $element) {
            $class = new \ReflectionClass($element);
            $behavior_class = $class->getNamespaceName() . '\Behavior';
            $this->attachBehavior($element->name, [
                'class' => $behavior_class::className(),
                'attribute' => $element->attributeName,
            ]);
        }
        foreach ($this->app->systemElements as $element) {
            $behavior_class = '\worstinme\zoo\elements\system\\' . $element->type . '\Behavior';
            $this->attachBehavior($element->name, [
                'class' => $behavior_class::className(),
                'attribute' => $element->attributeName,
            ]);
        }
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])
            ->viaTable('{{%items_categories}}', ['item_id' => 'id']);
    }

    public function getParentCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_category_id']);
    }

    public function getAlternates() {
        return $this->hasMany(Items::className(),['id'=>'alternate_id'])
            ->viaTable('{{%items_alternates}}',['item_id'=>'id']);
    }

    public function getItemsElements()
    {
        return $this->hasMany(ItemsElements::className(), ['item_id' => 'id']);
    }

    public function getUrl()
    {
        return ['/'.$this->app_id.'/item', 'id' => $this->id, 'lang'=>$this->lang];
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

    public function categoryTree($categories) {

        if (empty($categories)) {
            return null;
        }

        $categories = (array) $categories;

        $related = (new \yii\db\Query())
            ->select('id')
            ->from('{{%categories}}')
            ->where(['parent_id'=>$categories,'state'=>1])
            ->column();

        if (count($related) > 0) {
            $related = $this->categoryTree($related);
        }

        return array_merge($categories,$related);
    }

}
