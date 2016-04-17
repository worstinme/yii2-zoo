<?php

namespace worstinme\zoo\models;

use Yii;

class Menu extends \yii\base\Model {


	public static function get($alias,$items = []) {

        $zoo_items = [];

		return $items;
	}

   /* public function processItem($menu) {

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

    } */

}