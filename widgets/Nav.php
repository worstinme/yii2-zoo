<?php
namespace worstinme\zoo\widgets;

use Yii;
use worstinme\zoo\backend\models\Menu;

class Nav extends \worstinme\uikit\Nav
{
    public $menu;

    public function init()
    {

        if ($this->menu !== null) {

            $items = $this->items;

            $menus = Menu::find()->where(['menu'=>$this->menu])->andWhere('parent_id IS NULL')->all();

            foreach ($menus as $menu) {

                $items[] = $this->processItem($menu);

            }

            $this->items = $items;
        }
        
        parent::init();

    }


    public function processItem($menu) {

        $item = [
            'label' => $menu->label,
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