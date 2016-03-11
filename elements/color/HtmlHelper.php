<?php

namespace worstinme\zoo\elements\color;

use yii\helpers\ArrayHelper;

class HtmlHelper extends \yii\helpers\Html {


    public static function checkboxList($name, $selection = null, $items = [], $options = [])
    {
        if (substr($name, -2) !== '[]') {
            $name .= '[]';
        }

        $formatter = ArrayHelper::remove($options, 'item');
        $itemOptions = ArrayHelper::remove($options, 'itemOptions', []);
        $encode = ArrayHelper::remove($options, 'encode', true);
        $separator = ArrayHelper::remove($options, 'separator', "\n");
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        $lines = [];
        $index = 0;
        foreach ($items as $value) {
            $checked = $selection !== null &&
                (!is_array($selection) && !strcmp($value, $selection)
                    || is_array($selection) && in_array($value, $selection));
            if ($formatter !== null) {
                $lines[] = call_user_func($formatter, $index, $value, $name, $checked, $value);
            } else {
                $lines[] = static::checkbox($name, $checked, array_merge($itemOptions, [
                    'value' => $value,
                    'label' => false,
                    'class' => $value,
                ]));
            }
            $index++;
        }

        if (isset($options['unselect'])) {
            // add a hidden field so that if the list box has no option being selected, it still submits a value
            $name2 = substr($name, -2) === '[]' ? substr($name, 0, -2) : $name;
            $hidden = static::hiddenInput($name2, $options['unselect']);
            unset($options['unselect']);
        } else {
            $hidden = '';
        }

        $visibleContent = implode($separator, $lines);

        if ($tag === false) {
            return $hidden . $visibleContent;
        }

        return $hidden . static::tag($tag, $visibleContent, $options);
    }

	
}