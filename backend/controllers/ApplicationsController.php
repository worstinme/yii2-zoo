<?php

/**
 * @link https://github.com/worstinme/yii2-zoo
 * @copyright Copyright (c) 2017 Eugene Zakirov
 * @license https://github.com/worstinme/yii2-zoo/LICENSE
 */

namespace worstinme\zoo\backend\controllers;

use worstinme\zoo\backend\ApplicationsContent;
use Yii;

class ApplicationsController extends Controller
{
    public function actionIndex()
    {
        $this->subnav = false;

        $applications = Yii::$app->zoo->applications;

        return $this->render('index',[
            'applications'=>$applications,
        ]);
    }

    public function actionView($app)
    {
        $app = Yii::$app->zoo->getApplication($app);

        return $this->render('view',[
            'app'=>$app,
        ]);
    }

    public function actionUpdate($app, $lang)
    {
        if(($model = ApplicationsContent::findOne(['app_id'=>$app,'lang'=>$lang])) === null) {
            $model = new ApplicationsContent([
                'app_id'=>$app,
                'lang'=>$lang,
            ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('zoo','SAVE_SUCCESSEFULL'));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

}