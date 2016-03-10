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

            $menus = Menu::find()->where(['menu'=>$this->menu])->all();

            foreach ($menus as $menu) {

                $item = [
                    'label' => $menu->label,
                    'encodeLabels'=>false,
                    'url' => $menu->getUrl(),
                ];

                if ($menu->active !== null) {
                    $item['active'] =  $menu->active;
                }
                
                $items[] = $item;

            }

            $this->items = $items;
        }
        
        parent::init();

    }
}