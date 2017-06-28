<?php
namespace worstinme\zoo\widgets;

use Yii;
use worstinme\zoo\backend\models\Menu as MenuModel;

class Menu extends \worstinme\uikit\Nav
{
    public $name;
    public $menu;
    public $options;
    public $cache;

    public function init()
    {

        if ($this->menu !== null) {

            $cache_key = 'menu-'.$this->menu;

            $items = Yii::$app->cache->get($cache_key);

            if (true || $items === false) {

                $items = $this->items;

                $menus = MenuModel::find()->where(['menu'=>$this->menu])->andWhere('parent_id IS NULL')->orderBy('sort')->all();

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


        if ($menu->type == 6) {
            $item = '<div class="html">'.$menu->content.'</div>';
        }
        else {
            $item = [
                'label' => $menu->name,
                'encodeLabels'=>false,
                'url' => $menu->getUrl(),
            ];
        }



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