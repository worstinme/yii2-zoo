<?php
/* @var $this yii\web\View */
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;


?>

<?= ElFinder::widget([
    'language'         => 'ru',
    'frameOptions'	   =>['style'=>'height:100%;width:100%;'],
    'containerOptions' =>['style'=>'height:calc(100vh - 70px);width:100%;'],
    'controller'       => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
    'filter'           => ['image','application','text','video'],    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
    'callbackFunction' => new JsExpression('function(file, id){}') // id - id виджета
]); ?>
