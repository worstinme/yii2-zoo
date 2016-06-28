<?php

namespace worstinme\zoo\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@worstinme/zoo/assets';

    public $css = [
        'css'=>'css/admin.css',
        'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,500,700,300,400italic,900&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,400italic,900&subset=latin,cyrillic',
    ];

    public $js = [
        
    ];

    public $depends = [
        'worstinme\uikit\UikitAsset',
        'worstinme\uikit\assets\Notify',
    ];

    public $publishOptions = [
        'forceCopy'=> YII_ENV_DEV ? true : false,
    ];
}