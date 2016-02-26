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
                
                if ($menu->type == 4) {
                    $url = \yii\helpers\Json::decode($menu->url);
                }
                elseif ($menu->type == 5) {
                    $url = $menu->url;
                }

                $items[] = [
                    'label' => $menu->label,
                    'encodeLabels'=>false,
                    'url' => $url,
                ];

            }

            $this->items = $items;
        }
        
        parent::init();

    }
}