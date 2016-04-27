<?php echo "<?php"; ?>

namespace app\controllers;

use Yii;

<?php if (!empty($modelName)): ?>
use app\models\<?=$modelName?> as Items;
<?php else: ?>
use worstinme\zoo\models\Items;
<?php endif ?>
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\S as s;

use yii\web\NotFoundHttpException;

class <?= $controllerName ?> extends \worstinme\zoo\Controller
{

/*
    protected function renderApplication($app = null) {

        $app = $app === null ? $this->app : $app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams); 

        return $this->render('application', [
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
*/

/*
    protected function renderItem($model) {

        return $this->render('item',[
            'model'=>$model,
        ]);
    }
*/

/*
    protected function renderCategory($category) {

        $app = $category->app;

        $searchModel = new s();
        $searchModel->app_id = $app->id;
        $searchModel->regBehaviors();
        $searchModel->category = [$category->id];

        $dataProvider = $searchModel->data(Yii::$app->request->queryParams);

        return $this->render('category',[
            'category'=>$category,
            'app'=>$app,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
*/
}
