<?php

namespace worstinme\zoo\assets;

use yii\web\AssetBundle;

class AdminAssets extends AssetBundle
{
    public $sourcePath = '@worstinme/zoo/assets';

    public $css = [
        'css'=>'css/admin.css',
    ];

    public $js = [
        
    ];

    public $depends = [
        'worstinme\uikit\UikitAsset',
    ];

    public $publishOptions = [
        'forceCopy'=>true,
    ];
}