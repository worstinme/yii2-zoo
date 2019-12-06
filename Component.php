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
use yii\i18n\PhpMessageSource;
use yii\web\NotFoundHttpException;

/**
 * @property Application[]|Application $applications
 */
class Component extends \yii\base\Component implements BootstrapInterface
{
    /** @var boolean load backend module? */
    public $backend = false;

    /** @var array options for backend module class */
    public $backendModuleOptions = [];

    /** @var array loaded applications objects */
    public $applications = [];

    /** @var array List of roles with access to admin'a part of application. */
    public $adminAccessRoles = ['admin'];

    /** @var array List of roles with access to moderator's part of application. */
    public $moderAccessRoles = ['admin', 'moder'];

    /** @var array|boolean url component config */
    public $urlRuleComponent = [];

    /** @var array application content languages * */
    public $languages = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'zoo';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '' => 'applications/index',
        '<controller:\w+>' => '<controller>/index',
        '<controller:\w+>/<action:(\w|-)+>' => '<controller>/<action>',
    ];

    /** @var array Custom elements [namespace => path] */
    public $elements_paths = [
        '\app\components\elements' => '@app/components/elements'
    ];

    public $elfinder = [
        'baseUrl' => '@web',
        'basePath' => '@webroot',
        'path' => '/images/',
        'name' => '/images/',
    ];

    public function __construct(array $config = [])
    {
        $config['urlRuleComponent'] = ArrayHelper::merge([
            'class'=>'worstinme\zoo\components\UrlRule',
        ],$config['urlRuleComponent']??[]);

        if (Yii::$app->id == 'app-backend') {

            $this->adminAccessRoles = ['@'];
            $this->backend = true;

            if (!isset($config['urlRuleComponent']['host'])) {
                $host = explode(".", Yii::$app->request->hostName);
                if (count($host) > 2) {
                    $config['urlRuleComponent']['host'] = str_replace("//" . $host[0] . '.', "//", Yii::$app->request->hostInfo);
                }
            }

            $this->elfinder = [
                'path'=>'/images/',
                'baseUrl' => $config['urlRuleComponent']['host'],
                'basePath' => '@frontend/web',
                'name' => '@frontend/web/images/'
            ];
        }

        if (Yii::$app->id == 'basic') {
            $this->backend = true;
            $this->urlPrefix = 'admin/zoo';
        }

        parent::__construct($config);
    }

    public function init()
    {
        if (!is_array($this->languages) || !count($this->languages)) {
            $this->languages = [
                Yii::$app->language => Yii::$app->language,
            ];
        }
        parent::init();
    }

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

        Yii::beginProfile('register_applications', 'app');

        $applications = [];

        foreach ($app->zoo->applications as $application) {

            if (!isset($application['class'])) {
                $application['class'] = '\worstinme\zoo\Application';
            }

            $applications[$application['id']] = Yii::createObject($application);

            $app_id = $applications[$application['id']]->app_id;

            if ($this->backend or ($app->id === 'app-basic' || $app->id === 'app-frontend') && $app_id === null or $app_id === $app->id) {
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

            $app->setModule('zoo', ArrayHelper::merge([
                'class' => Module::className(),
            ], $this->backendModuleOptions));

            if (!isset($app->get('i18n')->translations['zoo*'])) {
                $app->get('i18n')->translations['zoo*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => '@worstinme/zoo/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }

            if (!isset($app->controllerMap['elfinder'])) {
                $app->controllerMap['elfinder'] = [
                    'class' => 'mihaildev\elfinder\Controller',
                    'access' => $this->adminAccessRoles,
                    'roots' => [],
                ];
            }

            if (!isset($app->controllerMap['elfinder']['roots']['frontend'])) {
                $app->controllerMap['elfinder']['roots']['frontend'] = $this->elfinder;
            }

            foreach ($app->zoo->applications as $application) {

                if (!empty($application->baseUrl) && !empty($application->basePath)) {

                    $root = [
                        'path' => '/images/' . $application->id . '/',
                        'name' => $application->id,
                        'baseUrl' => $application->baseUrl,
                        'basePath' => $application->basePath,
                    ];

                    if (!is_dir(Yii::getAlias($application->basePath . $root['path']))) {
                        if (!mkdir($concurrentDirectory = Yii::getAlias($application->basePath.$root['path']), 0757,
                                true) && !is_dir($concurrentDirectory)) {
                            throw new \RuntimeException(sprintf('Directory "%s" was not created',
                                $concurrentDirectory));
                        }
                    }

                    $app->controllerMap['elfinder']['roots'][$application->id] = $root;

                }

            }
        }

    }

    public function getFrontendHost() {
        if (isset($this->urlRuleComponent['host'])) {
            return $this->urlRuleComponent['host'];
        }
        return Yii::$app->request->hostInfo;
    }

}
