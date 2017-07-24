<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace worstinme\zoo\elements\image_uikit;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@worstinme/zoo/elements/image_uikit';
    public $css = [
        'style.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'worstinme\uikit\UikitAsset',
    ];
}
