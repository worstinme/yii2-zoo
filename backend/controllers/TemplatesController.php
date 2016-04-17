<?php
/**
 * @link https://github.com/worstinme/yii2-user
 * @copyright Copyright (c) 2014 Evgeny Zakirov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace worstinme\zoo\backend\controllers;

use Yii;
use worstinme\zoo\helpers\AppHelper;
use yii\web\NotFoundHttpException;

class TemplatesController extends Controller
{

    public function actionIndex() {
        
        $app = $this->getApp();

        $templates = $this->module->templates;

        $custom_templates = [];

        return $this->render('index', [
            'templates'=>$templates,
            'custom_templates'=>$custom_templates,
        ]);

    }

    public function actionRenderer($renderer = null) {

        $app = $this->getApp();

        $name = Yii::$app->request->get('template');

        $template = $app->getTemplate($name);

        $template['renderer'] = !empty($template['renderer']) ? $template['renderer'] : null;

        if ($renderer !== null) {
            $template['renderer'] = $renderer;
        }

        $renderersNames = $this->module->renderers;

        return $this->render('renderer', [ 
            'name'=>$name,
            'template'=>$template,
            'renderersNames'=>$renderersNames,
            'configView'=>$this->getRendererConfigView($template['renderer']),
        ]);

    }
    public function actionTemplateSave($renderer = null) {

        $request = Yii::$app->request;

        $app = $this->getApp();

        if($request->isPost) {  

            $rows = $request->post('rows');
            $renderer = $request->post('renderer');
            $rendererViewPath = $request->post('rendererViewPath');
            $name = $request->post('name');

            foreach ($rows as $key=>$row) {
                if (empty($row['items']) || !count($row['items'])) {
                    unset($rows[$key]);
                }
            }

            $app->setTemplate($name,['renderer'=>$renderer,'rendererViewPath'=>$rendererViewPath,'rows'=>$rows]);
            $app->save();     

            echo 'шаблон сохранен';
        }
        
    }


    public function getRendererConfigView($renderer) {

        if (!empty($renderer)) {

            $userPath = rtrim(Yii::$app->zoo->renderersPath,"/");

            $path = $renderer."/config.php";

            if (is_file(Yii::getAlias($userPath."/".$path))) {
                return $userPath."/".$path;
            }
            elseif (is_file(Yii::getAlias("@worstinme/zoo/renderers/".$path))) {
                return "@worstinme/zoo/renderers/".$path;
            }

        }

        return null;
    }


}