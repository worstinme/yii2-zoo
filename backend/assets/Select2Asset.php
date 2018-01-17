<?php

namespace worstinme\zoo\backend\assets;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';

    public $css = [
        'css/select2.min.css',
    ];

    public $js = [
        'js/select2.js',
        'js/i18n/ru.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}