<?php

namespace worstinme\zoo\backend\commands;

use garyjl\simplehtmldom\SimpleHtmlDom;
use worstinme\zoo\backend\Module;
use Yii;
use yii\helpers\Json;
use yii\db\Query;

class MigrateController extends \yii\console\Controller
{

    public function actionIndex()
    {
        // смигрируем приложения
        $applications = (new Query())->select('*')->from('{{%applications}}')->all();

        /** @var $module Module */
        $module = Yii::$app->getModule('zoo');

        echo $module->applicationsConfigPath;

        foreach ($applications as $app) {

            $text = '<?php return ['.PHP_EOL;
            $text .= "  'id' => '".$app['name']."',".PHP_EOL;
            $text .= "  'title' => '".$app['title']."',".PHP_EOL;
            $text .= "];";

            $fp = fopen(Yii::getAlias($module->applicationsConfigPath).DIRECTORY_SEPARATOR.$app['name'].".php", "w");
            fwrite($fp, $text);
            fclose($fp);

            echo $text;

        }

    }

}