<?php

namespace worstinme\zoo;

use worstinme\zoo\backend\Module;
use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use worstinme\zoo\models\Applications;
use worstinme\zoo\backend\models\Widgets;
use worstinme\zoo\backend\models\Config;
use yii\i18n\PhpMessageSource;
use yii\web\NotFoundHttpException;

/**
 * @property Application[]|Application $applications
 */
class Component extends \yii\base\Component implements BootstrapInterface
{
    /** @var boolean load backend module? */
    public $backend = false;

    /** @var array loaded applications objects */
    public $applications = [];

    /** @var array List of roles with access to admin'a part of application. */
    public $adminAccessRoles = ['admin'];

    /** @var array List of roles with access to moderator's part of application. */
    public $moderAccessRoles = ['admin', 'moder'];

    /** @var array|boolean url component config */
    public $urlRuleComponent = [
        'class' => 'worstinme\zoo\components\UrlRule',
    ];

    /**
     * @var string Applications configs storage folder
     *      e.g. @common/config/zoo
     */
    public $applicationsConfigPath = '@common/config/zoo';

    /** @var array application content languages * */
    public $languages = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'admin';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        'zoo' => 'applications/index',
        'zoo/<action:(\w|-)+>' => 'default/<action>',
        'zoo/<controller:\w+>/<action:(\w|-)+>' => '<controller>/<action>',
    ];

    /** @var array Custom elements [namespace => path] */
    public $elements_paths = [
        '\app\components\elements' => '@app/components/elements'
    ];

    public function getApplication($app_id, $throw_exception = true)
    {
        if (array_key_exists($app_id, $this->applications)) {
            return $this->applications[$app_id];
        }

        if ($throw_exception) {
            throw new NotFoundHttpException('The requested application does not exist.');
        }

        return null;
    }

    public function config($name, $default = null)
    {

        if (($config = Config::find()->where(['name' => $name])->one()) !== null) {
            return $config->value;
        }

        return $default;
    }

    public function getConfigs($name, $additions = [], $after = false)
    {
        $column = Config::find()->select('value')->where(['name' => $name])->indexBy('id')->column();
        if (count($additions) && is_array($additions)) {
            return $after ? $column + $additions : $additions + $column;
        }
        return $column;
    }

    public function getConfigValues($ids, $separator = ", ", $additions = [])
    {
        $column = Config::find()->select('value')->where(['id' => $ids])->column();
        if (count($additions) && is_array($additions)) {
            foreach ($ids as $id) {
                if (isset($additions[$id])) {
                    $column[] = $additions[$id];
                }
            }
        }
        return implode($separator, $column);
    }

    public function getLang()
    {
        return Yii::$app->request->get('lang', $this->defaultLang);
    }

    /** @return array Path with custom and system elements */
    public function getElementsPaths()
    {
        $paths = ['\worstinme\zoo\elements' => '@worstinme/zoo/elements'];
        return array_merge($paths, $this->elements_paths);
    }

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /* @var $module Module */

        if (!$app->hasModule('zoo')) {

            Yii::beginProfile('register_applications', 'app');

            $applications = [];

            $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias($this->applicationsConfigPath));

            foreach ($files as $file) {
                $application = require($file);
                if ($this->backend || !isset($application['app_id']) || $application['app_id'] == $app->id) {
                    if (!isset($application['class'])) {
                        $application['class'] = '\worstinme\zoo\Application';
                    }
                    $applications[$application['id']] = Yii::createObject($application);
                    $app->urlManager->addRules([
                        ArrayHelper::merge($this->urlRuleComponent, $applications[$application['id']]->urlRuleComponent, [
                            'app_id' => $application['id'],
                        ])
                    ], true);
                }
            }

            $app->zoo->applications = $applications;

            Yii::endProfile('register_applications', 'app');

            if ($app instanceof ConsoleApplication) {
                // load console module
            } elseif ($this->backend) {

                $configUrlRule = [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => $this->urlPrefix,
                    'rules' => $this->urlRules,
                ];
                if ($this->urlPrefix != 'zoo') {
                    $configUrlRule['routePrefix'] = 'zoo';
                }

                $rule = Yii::createObject($configUrlRule);

                $app->urlManager->addRules([$rule], false);

                $app->setModule('zoo', [
                    'class' => Module::className(),
                ]);

                if (!isset($app->get('i18n')->translations['zoo*'])) {
                    $app->get('i18n')->translations['zoo*'] = [
                        'class' => PhpMessageSource::className(),
                        'basePath' => '@worstinme/zoo/messages',
                        'sourceLanguage' => 'en-US'
                    ];
                }

                $roots = [];

                foreach ($applications as $application) {

                    $root = [
                        'path' => '/images/' . $application->id . '/',
                        'name' => $application->id,
                    ];

                    $root['baseUrl'] = $application->baseUrl !== null ? $application->baseUrl : '@web';
                    $root['basePath'] = $application->basePath !== null ? $application->basePath : '@webroot';

                    $roots[$application->id] = $root;

                    if (!is_dir(Yii::getAlias($root['basePath'] . $root['path']))) {
                        mkdir(Yii::getAlias($root['basePath'] . $root['path']), 0757, true);
                    }

                }

                $app->controllerMap['elfinder'] = [
                    'class' => 'mihaildev\elfinder\Controller',
                    'access' => $this->adminAccessRoles,
                    'roots' => $roots,
                ];
            }


        }

    }

}