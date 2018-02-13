<?php

/**
 * @link https://github.com/worstinme/yii2-zoo
 * @copyright Copyright (c) 2017 Eugene Zakirov
 * @license https://github.com/worstinme/yii2-zoo/LICENSE
 */

namespace worstinme\zoo\backend;

use Yii;

class Module extends \yii\base\Module
{

    /** @inheritdoc */
    public $controllerNamespace = 'worstinme\zoo\backend\controllers';

    /** @var string The ZOO admin layout. */
    public $layout = '@worstinme/zoo/backend/views/layouts/zoo';

    public function getNav()
    {
        return array_filter([
            ['label' => Yii::t('zoo', 'NAV_APPLICATIONS'), 'url' => ['/zoo/applications/index'],
                'items' => \yii\helpers\ArrayHelper::toArray(Yii::$app->zoo->applications, [
                    'worstinme\zoo\Application' => [
                        'label' => 'title',
                        'url' => function ($app) {
                            return ['/zoo/items/index', 'app' => $app->id];
                        },
                    ],
                ]),], ['label' => Yii::t('zoo', 'NAV_ELFINDER'), 'url' => ['/zoo/elfinder/index']],
            ['label' => Yii::t('zoo', 'NAV_MENU'), 'url' => ['/zoo/menu/index']],
            ['label' => Yii::t('zoo', 'NAV_CONFIG'), 'url' => ['/zoo/config/index']],
        ]);
    }

}