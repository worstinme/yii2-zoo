Yii content construction kit 
============================

Extension is under development and not ready to use.

Configuration
----------------------

1. Put in modules section of config:

```
'zoo' => [
    'class' => 'worstinme\zoo\backend\Module',
],
```

To replace backend module layouts (main.php or zoo.php) set view configurations for components section of Yii settings

``` 
'components'=>[
    ...
    'view' => [
       'theme' => [
           'pathMap' => [
               '@worstinme/zoo/backend/views/layouts' => '@backend/views/layouts',
           ],
       ],
    ],
],
```
       