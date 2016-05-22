<?php
namespace worstinme\zoo\widgets;

use Yii;
use worstinme\zoo\backend\models\Menu as MenuModel;

class Menu extends \worstinme\uikit\Nav
{
    public $name;

    public function init()
    {

        if ($this->name !== null) {

            $cache_key = 'menu-'.$this->name;

            $items = Yii::$app->cache->get($cache_key);

            if ($items === false) {

                $items = $this->items;

                $menus = MenuModel::find()->where(['menu'=>$this->name])->andWhere('parent_id IS NULL')->orderBy('sort')->all();

                foreach ($menus as $name) {

                    $items[] = $this->processItem($name);

                }

                $dependency = new \yii\caching\DbDependency(['sql' => 'SELECT MAX(updated_at) FROM '.MenuModel::tableName()]);

                Yii::$app->cache->set($cache_key, $items, 86400,$dependency);

            }

            $this->items = $items;
        }
        
        parent::init();

    }

    public function processItem($menu) {

        $item = [
            'label' => $menu->name,
            'encodeLabels'=>false,
            'url' => $menu->getUrl(),
        ];

        if ($menu->active !== null) {
            $item['active'] =  $menu->active;
        }

        if (count($menu->related) > 0) {
            foreach ($menu->related as $related) {
                $item['items'][] = $this->processItem($related);
            }
        }

        return $item;

    }
}