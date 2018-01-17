<?php

namespace worstinme\zoo\backend\assets;

use yii\web\AssetBundle;

class CKEditor4Asset extends AssetBundle
{
    public $js = [
        '//cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}