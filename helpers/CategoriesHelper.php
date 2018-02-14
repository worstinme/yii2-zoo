<?php

namespace worstinme\zoo\helpers;

use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;

class CategoriesHelper extends Component
{
	public static function processCatlist($categories, $parent_id = 0, $delimiter = null, $array = [])
    {
        if (count($categories)) {
            foreach ($categories as $key => $category) {
                if ($category['parent_id'] == $parent_id) {
                    $array[$category['id']] = (empty($delimiter) ? '' : $delimiter . ' ') . $category['name'];
                    unset($categories[$key]);
                    $array = self::processCatlist($categories, $category['id'], $delimiter . '—', $array);
                }
            }
        }

        return $array;
    }
}