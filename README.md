
============================
Yii content construction kit

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist worstinme/yii2-zoo "*"
```

or add

```
"worstinme/yii2-zoo": "*"
```

to the require section of your `composer.json` file.




CONFIG
------

config/web.php

```
'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'path' => 'images',
                'name' => 'images'
            ],
            'watermark' => [
                        'source'         => __DIR__.'/logo.png', // Path to Water mark image
                         'marginRight'    => 5,          // Margin right pixel
                         'marginBottom'   => 5,          // Margin bottom pixel
                         'quality'        => 95,         // JPEG image save quality
                         'transparency'   => 70,         // Water mark image transparency ( other than PNG )
                         'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
                         'targetMinPixel' => 200         // Target image minimum pixel size
            ]
        ]
    ],
```
url rules

```
'elfinder' => 'elfinder',
'elfinder/<_a:[\w\-]+>' => 'elfinder/<_a>',
```


